<?php

namespace App\Http\Controllers;

use App\Models\ProductDocument;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductDocument;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductDocument as ResourcesProductDocument;
use Illuminate\Support\Facades\Storage;
use App\Traits\Helpers\FileUpload;


class ProductDocumentsController extends Controller
{
    use FileUpload;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $product = $product->with('documents')->where('id', $product->id)->get();
        $product = (new ProductResource($product[0]))->response()->getData();
        return view('pages.product.documents')->with('product', $product->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductDocument $request, Product $product)
    {

        Validator::make($request->validated(), [
            'product_documents' => ['array'],
            'product_documents.*' => ['file', 'max: 2048'],
        ],
        [
            //custom messages
        ],
        [
            //custome attributes
        ])->validate();

        $productDocuments = $request->file('product_documents');

        if(!empty($productDocuments)) {

            foreach($productDocuments as $document) {
                if(isset($document))
                {
                    $documentInfo['path'] = $this->uploadFile($document, 'product/documents', 'product-document');
                    $documentInfo['title'] = $document->getClientOriginalName();
                }
                
                DB::beginTransaction();

                if($product->documents()->firstORCreate($documentInfo)) {
                    Session::flash('message', __('Document has been saved successfully'));
                    Session::flash('alert-class', 'alert-success');
                } else {
                    DB::rollback();
                    Session::flash('message', __('Unable to save document'));
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('admin.product.document-index',['product', $product->id]);
                }
            }

            DB::commit();

        } else {
            Session::flash('message', __('Unable to save document'));
            Session::flash('alert-class', 'alert-danger');

        }

        return redirect()->back();

    }

    public function show(Product $product, ProductDocument $productDocument)
    {
        $productDocument = (new ResourcesProductDocument($productDocument))->response()->getData();

        return Storage::download($productDocument->data->path, $productDocument->data->title);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductDocument  $productDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, ProductDocument $productDocument)
    {
        if($productDocument->delete()) {
            Session::flash('message', __('Document deleted successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete document'));
            Session::flash('alert-class', 'alert-danger');
        }

        return redirect()->back();
    }
}
