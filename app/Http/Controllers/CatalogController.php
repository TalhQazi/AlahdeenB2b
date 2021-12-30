<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Traits\PaginatorTrait;
use App\Http\Resources\CatalogCollection;
use Illuminate\Support\Facades\Storage;
use Musonza\Chat\Facades\ChatFacade;
use App\Traits\ChatTrait;
use Illuminate\Support\Facades\Validator;
use App\Traits\Helpers\FileUpload;

class CatalogController extends Controller
{
    private $noOfItems;

    use FileUpload;

    public function __construct()
    {
        $this->authorizeResource(Catalog::class);
        $this->noOfItems = config('pagination.catalogs', config('pagination.default'));
    }

    public function index(Catalog $catalog, Request $request)
    {
        $catalogs = $catalog::where('user_id','=',Auth::user()->id);

        if($request->input('q')) {
            $searchParam = $request->input('q');
            $catalogs = $catalogs->where('title', 'like', '%'.$searchParam.'%');
        }

        $catalogs = $catalogs->paginate($this->noOfItems);
        $catalogs = (new CatalogCollection($catalogs))->response()->getData();

        return view('pages.catalog.index')->with([
            'catalogs' => $catalogs,
            'paginator' => PaginatorTrait::getPaginator($request, $catalogs),
        ]);
    }

    public function create()
    {
        return view('pages.catalog.create');
    }

    public function store(Request $request, Catalog $catalog)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'catDocument' => 'required|file|max:2048'
        ], [], [
            'catDocument' => __('Catalog Document File')
        ]);

        try {
            DB::beginTransaction();


            $details = $request->only(['title']);
            if(isset($request->catDocument))
            {
                $details['path'] = $this->uploadFile($request->catDocument, 'documents/'.uniqid(), 'catalog');
            }
            $details['user_id'] = Auth::user()->id;

            $catalog = $catalog->firstOrCreate($details);

            DB::commit();

            Session::flash('message', __('Catalog has been saved successfully'));
            Session::flash('alert-class', 'alert-success');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', __($e->getMessage()));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->route('catalog.home');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Catalog $catalog)
    {
        if($catalog->delete()) {
            Storage::delete($catalog->path);
            Session::flash('message', __('Catalog has been removed successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to remove Catalog.'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();

    }

    public function sendCatalog($conversationId, Request $request)
    {
        if($request->ajax()) {

            Validator::make(
                $request->input(),
                [
                    'catalog_path' => ['required', 'string'],
                    'message'      => ['nullable', 'string']
                ],
                [
                    'catalog_path.required' => 'Catalog needs to be selected'
                ],
                [
                    'catalog_path' => __('Catalog')
                ]
            )->validate();

            $type = 'catalog';
            $sentMessage[] = __('Catalog');

            if(!empty($request->message)) {
                $message = $request->message . __(' Please find my Catalog link: ') . '<a href="' . url('') . Storage::url($request->catalog_path) . '" target="_blank">' . url('') . Storage::url($request->catalog_path) . '</a>';
            } else {
                $message = __('Please find my Catalog link: ') . '<a href="' . url('') . Storage::url($request->catalog_path) . '" target="_blank">' . url('') . Storage::url($request->catalog_path) . '</a>';
            }
            $conversation = ChatTrait::getConversation($conversationId);
            ChatTrait::sendMessage($conversation, Auth::user(), $message, 'catalog');

            return response()->json(['sent_message' => $sentMessage]);

        } else {

            redirect()->route('chat.messages');
        }

    }
}
