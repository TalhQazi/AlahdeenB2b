<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Exception;
use Musonza\Chat\Chat;
use App\Models\BusinessDetail;

class ClientController extends Controller
{

    protected $accountTypes;

    public function __construct()
    {
        $this->accountTypes = config('account_types');
    }

    public function index(Chat $chat)
    {
        // get all conversations of the current user
        $conversations = Auth::user()->conversations();

        // extract unique participants
        $participants = [];
        foreach ($conversations as $conversation) {
          if(!empty($conversation->data)) {
            $userId = NULL;

            if(!empty($conversation->data['user_id']) && Auth::user()->id <> $conversation->data['user_id']){
              $userId = $conversation->data['user_id'];
            } else if(!empty($conversation->data['buyer_id']) && Auth::user()->id <> $conversation->data['buyer_id']) {
              $userId = $conversation->data['buyer_id'];
            } else if(!empty($conversation->data['seller_id']) && Auth::user()->id <> $conversation->data['seller_id']) {
              $userId = $conversation->data['seller_id'];
            }

            if(!empty($userId) ) {
              $participants[$userId] = $userId;
            }
          }
        }

        $existingClients = Client::select('client_id')->where('user_id', Auth::user()->id)->get()->pluck('client_id');
        $existingClientIds = [];
        foreach($existingClients as $existingClient) {
            $existingClientIds[$existingClient] = $existingClient;
        }
        $existingClientIds = array_diff($participants, $existingClientIds);
        $existingClients = User::select('id', 'name')->whereIn('id', $existingClientIds)->get();
        $clients = Client::with('client', 'business')->where('user_id', Auth::user()->id)->get();

        return view('pages.khata.clients')
            ->with([
                'clients' => $clients,
                'existingClients' => $existingClients
            ]);
    }

    public function store(Request $request)
    {
        
        if ($request->existingClient) {
            $user = User::find($request->existingClient);
        } else {
            $user = $this->createNewUser($request);
        }
        $bd = new BusinessDetail();
        $bd->user_id = $user->id;
        $bd->company_name = $request->company_name;
        $bd->phone_number = $request->phone_full;
        $bd->city_id = $request->city_id;
        $bd->save();
        $response = $this->addClient($user);

        if ($request->ajax()) {
            return response()->json($response);
        }

        Session::flash('message', $response['message']);
        Session::flash('alert-class', $response['alert-class']);

        return redirect()->back();
    }

    /**
     * Create New User and send invite
     */
    protected function createNewUser(Request $request)
    {
        Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
                'phone_full' => ['required', 'string', 'max:20', 'unique:users,phone'],
                'city_id' => ['required', 'integer']
            ],
            [
                // custom validation messages
            ],
            [
                // custom attribute values
                'phone_full' => 'phone number',
            ]
        )->validate();

        return DB::transaction(function () use ($request) {
            try {
                $token = substr(md5(rand(0, 9) . $request->phone_full . time()), 0, 32);
                return tap(
                    User::create([
                        'name' => $request->name,
                        'email' => $request->email ?? null,
                        'phone' => $request->phone_full,
                        'password' => $token,
                        'city_id' => $request->city_id,
                        'is_active' => false,
                    ]),
                    function (User $user) use ($token) {
                        // assign role to user based on selected account type
                        $user->assignRole($this->accountTypes[5]);

                        // create and send invitation
                        Invitation::create([
                            'user_id' => $user->id,
                            'token' => $token,
                        ]);
                    }
                );
            } catch (Exception $e) {
                abort(422, __('some error occured while creating new client'));
            }
        });
    }

    /**
     * Add entry to clients table
     *
     * @param \App\Models\User $user
     * @return \App\Models\Client create
     */
    private function addClient(User $user)
    {
        $response = [
            'message' => __('Something went wrong, Please try again.'),
            'alert-class' => 'alert-error'
        ];

        if ($user && $user->id <> Auth::user()->id) {
            if (Client::create([
                'user_id' => Auth::user()->id,
                'client_id' => $user->id,
            ])) {
                $response['message'] = __('Client Added Successfully.');
                $response['alert-class'] = 'alert-success';
            }
        } else {
            $response['message'] = __('User mismatched or not found.');
        }

        return $response;
    }

    public function getClientBusinessDetails(Request $request, User $client)
    {
        $data = [
            'client' => $client,
            'business_city' => !empty($client->business) ? $client->business->businessCity : $client->city,
            'business_details' => $client->business
        ];

        if ($request->ajax()) {
            return response()->json($data);
        }

        abort(400, 'bad request');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return redirect()->back();
     */
    public function destroy(Client $client)
    {
        if ($client->delete()) {
            Session::flash('message', __('Client has been removed successfully.'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to remove Client.'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }
}
