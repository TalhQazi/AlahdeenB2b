<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestimonial;
use App\Http\Resources\Testimonial as ResourcesTestimonial;
use App\Http\Resources\TestimonialCollection;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Traits\PaginatorTrait;
use Illuminate\Support\Facades\Session;
use App\Traits\Helpers\FileUpload;

class TestimonialController extends Controller
{
    use FileUpload;
    private $noOfItems;

    public function __construct()
    {
        $this->authorizeResource(Testimonial::class);
        $this->noOfItems = config('pagination.testimonial', config('pagination.default'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Testimonial $testimonial, Request $request)
    {
        $testimonials = $testimonial::withTrashed()->orderBy('created_at','desc');

        if($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $testimonials = $testimonials->where('user_name', 'like', '%'.$searchParam.'%')->orWhere('designation', 'like', '%'.$searchParam.'%');
        }

        $testimonials = $testimonials->paginate($this->noOfItems);
        $testimonials = (new TestimonialCollection($testimonials))->response()->getData();


        if ($request->ajax()) {

             return response()->json(['testimonials' => $testimonials, 'paginator' => (string) PaginatorTrait::getPaginator($request, $testimonials)->links()]);

        } else {
            return view('pages.testimonial.index')->with([
                'testimonials' => $testimonials->data,
                'paginator' => PaginatorTrait::getPaginator($request, $testimonials)
            ]);
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTestimonial $request)
    {
        $validatedData = $request->validated();
        $productImage = $validatedData['image_path'];
        if(isset($productImage))
        {
            $testImage = $this->uploadFile($productImage, 'testimonial/images', 'testimonial-image');
        }
        $created = Testimonial::create([
            'user_name' => $validatedData['user_name'],
            'designation' => $validatedData['designation'],
            'company_name' => $validatedData['company_name'],
            'company_website' => $validatedData['company_website'],
            'message' => $validatedData['message'],
            'image_path' =>  $testImage,
        ]);

        if($created) {
            Session::flash('message', __('Testimonial has been saved successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to save testimonial'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Testimonial $testimonial, Request $request)
    {
        $testimony = (new ResourcesTestimonial($testimonial))->response()->getData();

        if($request->ajax()) {
            return response()->json(['data' => $testimony->data]);
        } else {
            return $testimony->data;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTestimonial $request, Testimonial $testimonial)
    {
        $validatedData = $request->validated();

        $testimonyDetails = $request->only(['user_name', 'designation', 'company_name', 'company_website', 'message']);
        $productImage = $validatedData['image_path'];
        if(isset($productImage))
        {
            $testimonyDetails['image_path'] = $this->uploadFile($productImage, 'testimonial/images', 'testimonial-image');
        }
        // ddd($testimonyDetails);
        if($testimonial->update($testimonyDetails)) {
            Session::flash('message', __('Testimonial has been updated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to update testimonial'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $testimonial)
    {

        if($testimonial->delete()) {
            Session::flash('message', __('Testimonial has been deleted successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete testimonial'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

    public function restore(Testimonial $testimonial, $id)
    {

        if($testimonial::withTrashed()->findOrFail($id)->restore()) {
            Session::flash('message', __('Testimonial has been reactivated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to restore testimonial'));
            Session::flash('alert-class', 'alert-error');

        }

        return redirect()->back();

    }
}
