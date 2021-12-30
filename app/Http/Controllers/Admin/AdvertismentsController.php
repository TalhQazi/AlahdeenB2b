<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdvertisment;
use App\Http\Resources\Advertisment as ResourcesAdvertisment;
use App\Http\Resources\AdvertismentCollection;
use App\Models\Advertisment;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Traits\Helpers\FileUpload;

class AdvertismentsController extends Controller
{
    use FileUpload;
    private $noOfItems;

    public function __construct()
    {
        $this->authorizeResource(Advertisment::class);
        $this->noOfItems = config('pagination.advertisment', config('pagination.default'));
    }


    public function index(Request $request)
    {
        $advertisments = Advertisment::paginate($this->noOfItems);
        $advertisments = (new AdvertismentCollection($advertisments))->response()->getData();

        return view('pages.advertisment.index')->with([
            'advertisments' => $advertisments,
            'paginator' => PaginatorTrait::getPaginator($request, $advertisments)
        ]);
    }

    public function create()
    {
        return view('pages.advertisment.create')->with(
            [
                'display_section' => config('display_configuration.advertisments'),
            ]
        );
    }

    public function store(StoreAdvertisment $request, Advertisment $advertisment)
    {
        $message = __('Unable to save advertisment details');
        $alertClass = 'alert-error';

        $validatedData =  $request->validated();
        $productImage = $validatedData['image_path'];
        if(isset($productImage))
        {
            $advertisment->image_path = $this->uploadFile($productImage, 'admin-ads/images', 'admin-ad-image');
        }
        $advertisment->url_link = $validatedData['url_link'] ?? null;
        $advertisment->is_active = !empty($validatedData['is_active']) ? 1 : 0;
        $advertisment->display_order = $validatedData['display_order'];
        $advertisment->display_section = $validatedData['display_section'];

        if($advertisment->save()) {
            Session::flash('message', __('Advertisment has been saved successfully'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('admin.advertisments.home');
        } else {
            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return redirect()->back();
        }
    }

    public function edit(Advertisment $advertisment)
    {
        $advertisment = (new ResourcesAdvertisment($advertisment))->response()->getData();
        return view('pages.advertisment.edit')->with([
            'advertisment' => $advertisment->data
        ]);
    }

    public function update(Advertisment $advertisment, StoreAdvertisment $request)
    {
        $message = __('Unable to update advertisment details');
        $alertClass = 'alert-error';

        $validatedData = $request->validated();
        $productImage = $validatedData['image_path'];
        if(isset($productImage))
        {
            $advertisment->image_path = $this->uploadFile($productImage, 'admin-ads/images', 'admin-ad-image');
        }

        $advertisment->url_link = $validatedData['url_link'] ?? null;
        $advertisment->is_active = !empty($validatedData['is_active']) ? 1 : 0;
        $advertisment->display_order = $validatedData['display_order'];

        if($advertisment->save()) {
            Session::flash('message', __('Advertisment has been updated successfully'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('admin.advertisments.home');
        } else {
            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return redirect()->back();
        }
    }

    public function destroy(Advertisment $advertisment)
    {
        if($advertisment->delete()) {
            Session::flash('message', __('Advertisment has been deleted successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to remove Advertisment.'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }


}
