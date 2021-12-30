<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChallanRequest;
use App\Models\Challan;
use App\Models\User;
use App\Traits\ChatTrait;
use App\Traits\FileTrait;
use App\Traits\ImageTrait;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Musonza\Chat\Models\Conversation;
use App\Traits\Helpers\MyFileUpload;

class ChallanController extends Controller
{
    use MyFileUpload;
    use ImageTrait, FileTrait, ChatTrait;

    public function __construct()
    {
      $this->authorizeResource(Challan::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if($request->session()->get('user_type') == "seller") {
        $challans = Challan::with(['toUser', 'fromUser', 'toUser.business', 'fromUser.business'])->where('from', Auth::user()->id)->get();
      } else {
        $challans = Challan::with(['toUser', 'fromUser', 'toUser.business', 'fromUser.business'])->where('to', Auth::user()->id)->get();
      }


      return view('pages.khata.challans')->with([
        'challans' => $challans
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $data['clients'] = User::with('business')->whereIn('id', Auth::user()->clients->pluck('client_id'))->get();
      if($data['clients']->isEmpty()) {
        Session::flash('message', __('Clients needs to be added or invited before trying to create Invoice'));
        Session::flash('alert-class', 'alert-error');

        return redirect()->route('khata.client.home');
      } else {
        $data['user'] = Auth::user();
        $data['company_logo'] = !empty(Auth::user()->business->additionalDetails->logo) ? Storage::url(Auth::user()->business->additionalDetails->logo) : NULL;
        return view('pages.khata.create_challan', $data);
      }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChallanRequest $request)
    {

        // dd($request->all());
        $validatedData = $request->validated();
        try
        {
            $digitalSignature = NULL;
            if(isset($validatedData['digital_signature']))
            {
                $productImage = $validatedData['digital_signature'];
                $digitalSignature = $this->uploadMyFile($productImage, 'invoice/challan', 'challan-image');
            }

            $createdChallan = tap(Challan::create([
                'from' => Auth::user()->id,
                'to' => $validatedData['client_id'],
                'challan_date' => $validatedData['challan_date'],
                'items_included' => $validatedData['items_included'],
                'no_of_pieces' => $validatedData['no_of_pieces'],
                'weight' => $validatedData['weight'],
                'bilty_no' => $validatedData['bilty_no'],
                'courier_name' => $validatedData['courier_name'],
                'digital_signature' => $digitalSignature
            ]), function(Challan $challan) {
                $this->createPdf($challan);
            });

            if($createdChallan) {
                if($request->send_via_chatbox) {
                    $resp = $this->sendChallanViaChat($createdChallan);

                    if($resp) {
                        return redirect()->route('chat.messages', ['conversation_id' => $resp]);
                    } else {
                        Session::flash('message', __('Unable to send Challan.'));
                        Session::flash('alert-class', 'alert-error');
                    }
                } else {
                    Session::flash('message', __('Challan has been saved successfully'));
                    Session::flash('alert-class', 'alert-success');
                }
            } else {
                Session::flash('message', __('Unable to save Challan'));
                Session::flash('alert-class', 'alert-error');
            }

            return redirect()->route('khata.challan.home');
        }catch(\Exception $e)
        {
            Session::flash('message', __($e->getMessage()));
            Session::flash('alert-class', 'alert-error');
        }

        


    }

    public function createPdf(Challan $challan)
    {
      if($challan->challan_path != '/') {
        $this->deleteFile($challan->challan_path);
      }

      $buyerDetails = User::with(['business'])->where('id', $challan->to)->get();
      $sellerDetails = User::with(['business', 'business.additionalDetails'])->where('id', Auth::user()->id)->get();

      if(!empty($sellerDetails[0]) && !empty($sellerDetails[0]->business) && !empty($sellerDetails[0]->business->additionalDetails)) {
        $sellerDetails[0]->business->additionalDetails->logo = Storage::url($sellerDetails[0]->business->additionalDetails->logo);
      }

      $data['seller_details'] = $sellerDetails[0];
      $data['buyer_details'] = $buyerDetails[0];

      $challanInfo = clone $challan;
      $challanInfo->digital_signature = Storage::url($challanInfo->digital_signature);

      $data['challanInfo'] = $challanInfo;

      $pdf = PDF::loadView('components.khata.challan-pdf', ['data' => $data]);
      $fileName =  time() . '.' . 'pdf';

      $content = $pdf->download()->getOriginalContent();
      $this->putFile('public/khata/challan/', $content, $fileName);

      $pdfPath = 'public/khata/challan/'.$fileName;

      $challan->challan_path = $pdfPath;
      $challan->save();

    }

    /**
     * Return pdf file associated with the specified resource
     *
     * @param  \App\Models\Challan  $challan
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Challan $challan)
    {
      $this->authorize('download', $challan);
      return Storage::download($challan->challan_path);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Challan  $challan
     * @return \Illuminate\Http\Response
     */
    public function edit(Challan $challan)
    {
      $data['clients'] = User::with('business')->whereIn('id', Auth::user()->clients->pluck('client_id'))->get();
      if($data['clients']->isEmpty()) {
        Session::flash('message', __('Clients needs to be added or invited before trying to create Invoice'));
        Session::flash('alert-class', 'alert-error');

        return redirect()->route('khata.client.home');
      } else {
        $buyerDetails = User::with(['business'])->where('id', $challan->to)->get();
        $sellerDetails = User::with(['business', 'business.additionalDetails'])->where('id', Auth::user()->id)->get();

        if(!empty($sellerDetails[0]) && !empty($sellerDetails[0]->business) && !empty($sellerDetails[0]->business->additionalDetails)) {
          $sellerDetails[0]->business->additionalDetails->logo = Storage::url($sellerDetails[0]->business->additionalDetails->logo);
        }

        $data['seller_details'] = $sellerDetails[0];
        $data['buyer_details'] = $buyerDetails[0];
        $challan->challan_date = Carbon::parse($challan->challan_date)->isoFormat('YYYY-MM-DD');
        $challan->digital_signature = Storage::url($challan->digital_signature);

        $data['challanInfo'] = $challan;
        return view('pages.khata.edit_challan', $data);
      }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Challan  $challan
     * @return \Illuminate\Http\Response
     */
    public function update(ChallanRequest $request, Challan $challan)
    {
      $validatedData = $request->validated();

      $digitalSignature = NULL;
      if( $validatedData['digital_signature'] ) {
        $digitalSignature = $this->uploadImage($validatedData['digital_signature'], 'public/digital-signatures');
        $this->deleteImage($challan->digital_signature);
      }

      $challan->from = Auth::user()->id;
      $challan->to = $validatedData['client_id'];
      $challan->challan_date = $validatedData['challan_date'];
      $challan->items_included = $validatedData['items_included'];
      $challan->no_of_pieces = $validatedData['no_of_pieces'];
      $challan->weight = $validatedData['weight'];
      $challan->bilty_no = $validatedData['bilty_no'];
      $challan->courier_name = $validatedData['courier_name'];
      $challan->digital_signature = $digitalSignature;

      if($challan->save()) {
        $this->createPdf($challan);
        Session::flash('message', __('Challan has been updated successfully'));
        Session::flash('alert-class', 'alert-success');
      } else {
        Session::flash('message', __('Unable to update challan'));
        Session::flash('alert-class', 'alert-error');
      }

      return redirect()->route('khata.purchase-order.home');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Challan  $challan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Challan $challan)
    {
      if ($this->deleteFile($challan->challan_path) && $this->deleteImage($challan->digital_signature) && $challan->delete()) {
        Session::flash('message', __('Challan has been removed successfully.'));
        Session::flash('alert-class', 'alert-success');
      } else {
          Session::flash('message', __('Unable to remove Challan.'));
          Session::flash('alert-class', 'alert-error');
      }

      return redirect()->back();
    }

    public function sendChallanViaChat(Challan $challan)
    {
        $conversation = ChatTrait::getParticipantsConversation(Auth::user(), User::find($challan->to));

        //Conversation doesn't exist between buyer and seller
        if (empty($conversation)) {
            $resp = $this->createChatSendChallan($challan);
        } else {
            $resp = $this->sendChallan($challan, $conversation);
        }

        return $resp;
    }

    public function createChatSendChallan(Challan $challan) {

        $conversation = ChatTrait::createConversation(Auth::user(), User::find($challan->to));
        ChatTrait::sendMessage($conversation, Auth::user(), __('Hi ') . $challan->toUser->name);

        return $this->sendChallan($challan, $conversation);
    }

    public function sendChallan(Challan $challan, Conversation $conversation)
    {
        $sent = $this->sendDeliveryChallan($conversation, $challan->challan_path, $challan->fromUser);
        if($sent) {
            return $conversation->id;
        } else {
            return false;
        }
    }

    public static function sendDeliveryChallan(Conversation $conversation, $deliveryChallan, User $from)
    {
        $sentMessage[] = '<div class="bg-white p-2"><a href="' . url(Storage::url($deliveryChallan)) . '" target="_blank"><img class="attachment" src="' . asset('/img/docs.png') . '"/></a></div>';
        ChatTrait::sendMessage($conversation, $from, $deliveryChallan, 'quotation');

        $sentMessage[] = $lastMessage = __('Please find the delivery challan');
        ChatTrait::sendMessage($conversation, $from, $lastMessage, 'text');

        return true;
    }
}
