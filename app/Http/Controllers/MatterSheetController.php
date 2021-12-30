<?php

namespace App\Http\Controllers;

use App\Helpers\LargeCSVReader;
use App\Http\Requests\MatterSheetProductRequest;
use App\Http\Resources\BusinessDirectorCollection;
use App\Models\CompanyPageProduct;
use App\Models\AdditionalBusinessDetail;
use App\Models\BusinessCertification;
use App\Models\BusinessDetail;
use App\Models\BusinessDirector;
use App\Models\Category;
use App\Models\MatterSheet;
use App\Models\MatterSheetProduct;
use App\Models\ModeOfPayment;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\City;
use App\Traits\ImageTrait;
use App\Traits\PackageUsageTrait;
use App\Traits\PaginatorTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Traits\Helpers\MyFileUpload;

class MatterSheetController extends Controller
{
    //
    use PackageUsageTrait;
    use ImageTrait;
    use MyFileUpload;

    private $noOfItems;

    public function __construct()
    {
        $this->noOfItems = config('pagination.products', config('pagination.default'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function home(BusinessDetail $businessDetail, City $city)
    {
        $this->authorize('create', BusinessDetail::class);
        $businessDetails = $businessDetail->where(['user_id' => Auth::user()->id])->first();

        return view('pages.matter-sheet.index')
            ->with('cities', $city->all())
            ->with('ownership_types', config('ownership_type'))
            ->with('business_details', $businessDetails)
            ->with('user', Auth::user())
            ->with('matter_sheet_return', true);
    }

    public function additionalDetails(BusinessDetail $businessDetail, ModeOfPayment $modeOfPayment, City $city)
    {
        $this->authorize('create', AdditionalBusinessDetail::class);
        $businessDetail = $businessDetail::where(['user_id' => Auth::user()->id])->get();

        $additionalBusinessDetails = [];
        if (!empty($businessDetail[0])) {

            $businessDetail = $businessDetail[0];
            $additionalBusinessDetails = $businessDetail->additionalDetails()->first();
            $businessModePayments = $businessDetail->businessModeOfPayments()->select('mode_of_payment_id')->get();
            $businessModePayments = !empty($businessModePayments) ? $businessModePayments->pluck('mode_of_payment_id')->all() : [];
            $businessPhotos = $businessDetail->businessPhotos()->select(['id', 'photo_path'])->get();
            $businessContacts = $businessDetail->businessContacts()->get()->toArray();
            $additionalPhotosIsAvailable = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'can_add_additional_detail_photos') == 1;

            return view('pages.profile.additional-business-details')->with(
                [
                    'additional_business_details' => $additionalBusinessDetails,
                    'mode_of_payments' => $modeOfPayment::getModeOfPayments(),
                    'business_mode_of_payments' => $businessModePayments,
                    'business_photos' => $businessPhotos,
                    'no_of_photos_allowed' => config('images_limit.no_of_company_photos'),
                    'business_contacts' => $businessContacts,
                    'division_types' => config('business_contacts.division_types'),
                    'business_id' => $businessDetail->id,
                    'business_days' => config('business_days_hours.days'),
                    'business_hours' => config('business_days_hours.hours'),
                    'additionalPhotosIsAvailable' => $additionalPhotosIsAvailable,
                    'hide_steps_bar' => true,
                    'cities' => $city->all(),
                    'matter_sheet_return' => true,
                    'ship_modes' => config('shipment_modes')
                ]
            );
        } else {
            Session::flash('message', __('Business details needs to be added before adding additional details'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('profile.matter.home');
        }
    }

    public function directorProfile()
    {
        if ($this->canUseFeature('can_add_director_profile')) {
            $businessDirectors = BusinessDirector::where('user_id', Auth::user()->id)->get();
            if (!empty($businessDirectors)) {
                $businessDirectors = (new BusinessDirectorCollection($businessDirectors))->response()->getData();
            }

            return view('pages.profile.director-profile')->with(
                [
                    'directors_profiles' => $businessDirectors->data,
                    'hide_steps_bar' => true,
                    'matter_sheet_return' => true,
                ]
            );
        } else {
            Session::flash('message', __('You need to upgrade your package inorder to add director profile'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('dashboard');
        }
    }

    public function certificateAndAwards(BusinessDetail $businessDetail)
    {
        $this->authorize('viewAny', BusinessCertification::class);

        $businessDetail = $businessDetail::select('id')->where(['user_id' => Auth::user()->id])->get();

        if (!empty($businessDetail[0])) {
            $businessDetail = $businessDetail[0];
            $certificates = $businessDetail->businessCertificates()->paginate(10);
            $awards = $businessDetail->businessAwards()->paginate(10);
            $certificateAwardsIsAvailable = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'can_add_certificates_n_awards') == 1;


            return view('pages.profile.certiticates-award')->with([
                'business_id' => $businessDetail->id,
                'business_certificates' => $certificates,
                'business_awards' => $awards,
                'certificateAwardsIsAvailable' => $certificateAwardsIsAvailable,
                'hide_steps_bar' => true,
                'matter_sheet_return' => true,
            ]);
        } else {
            Session::flash('message', __('Business details needs to be added before adding certifications'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('profile.business.home');
        }
    }

    public function productUpload()
    {
        $this->authorize('create', MatterSheet::class);

        return view('pages.matter-sheet.upload-products');
    }


    public function storeProduct(MatterSheetProductRequest $request)
    {
        $this->authorize('create', MatterSheet::class);
        $validatedData = $request->validated();


        $categoryIndex = count($validatedData['categories']);
        
        DB::beginTransaction();
        if(isset($validatedData['logo']))
        {
            $image = $this->uploadMyFile($validatedData['logo'], 'matter_sheet/images/', 'matter-sheet');
        }

        try
        {
            $ms = new MatterSheet();
            $ms->user_id = auth()->user()->id;
            $ms->category_id = $validatedData['categories'][$categoryIndex];
            $ms->save();

            $msp = new MatterSheet();
            $msp->image_path = $image;
            $msp->category_id = $validatedData['categories'][$categoryIndex];
            $msp->category = $validatedData['categories_name'][$categoryIndex];
            $msp->title = $validatedData['title'];
            $msp->product_code = $validatedData['product_code'];
            $msp->web_category = $validatedData['web_category'];
            $msp->brand_name = $validatedData['brand_name'];
            $msp->price = $validatedData['price'];
            $msp->approx_price = $validatedData['approx_price'];
            $msp->currency_1 = 'PKR';
            $msp->currency_2 = 'PKR';
            $msp->min_price = $validatedData['min_price'];
            $msp->max_price = $validatedData['max_price'];
            $msp->min_order_quantity = $validatedData['min_order_quantity'];
            $msp->unit_measure_quantity = $validatedData['unit_measure_quantity'];
            $msp->supply_ability = $validatedData['supply_ability'];
            $msp->unit_of_measure = $validatedData['unit_of_measure'];
            $msp->user_id = auth()->user()->id;

            Session::flash('message', __('Matter sheet product has been saved successfully'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('matter-sheet.matter_sheet_product.listing');

        }catch(\Exception $e)
        {
            Session::flash('message', $e->getMessage());
            Session::flash('alert-class', 'alert-error');
            return redirect()->back();
        }
    }

    public function storeProductFile(Request $request, MatterSheet $matterSheet)
    {

        $this->authorize('create', MatterSheet::class);

        $productLimit = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'no_of_products');
        if (MatterSheetProduct::where('user_id', Auth::user()->id)->get()->count() >= $productLimit) {
            Session::flash('message', __('You need to buy package addons, since the current subscriptions allow only ' . $productLimit . __(' products to be added.')));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('subscription.home');
        }

        $message = __('Unable to save matter sheet details');
        $alertClass = 'alert-error';

        $matter_sheet_file = NULL;
        if (!empty($request->file('matter_sheet_file'))) {

            $categoryIndex = count($request->bulk_categories);

            $matter_sheet_file = $this->uploadFile($request->matter_sheet_file, "public/matter_sheet/files/$matter_sheet_file");

            $matterSheet->category_id = $request->bulk_categories[$categoryIndex];
            $matterSheet->file_path = $matter_sheet_file;
            $matterSheet->user_id = Auth::user()->id;
            $matterSheet->save();


            $file = fopen(storage_path("app/$matter_sheet_file"), 'r');

            $csv_reader = new LargeCSVReader($file, ",");
            $cur_time = Date(now());

            foreach ($csv_reader->csvToArray() as $data) {
                // Preprocessing of the array.
                foreach ($data as $key => $entry) {
                    // Laravel doesn't add timestamps on its own when inserting in chunks.
                    $data[$key]['created_at'] = $cur_time;
                    $data[$key]['updated_at'] = $cur_time;
                    $data[$key]['category'] = $request->bulk_categories_name[$categoryIndex];
                    $data[$key]['category_id'] = $request->bulk_categories[$categoryIndex];

                    $data[$key]['matter_sheet_id'] = $matterSheet->id;
                }
                MatterSheetProduct::insertOrIgnore($data);
            }
        }


        Session::flash('message', __('Matter sheet has been saved successfully'));
        Session::flash('alert-class', 'alert-success');
        return redirect()->route('matter-sheet.matter_sheet.listing');

        return view('pages.matter-sheet.upload-products');
    }

    public function mattersheetListing(Request $request, MatterSheet $matterSheet)
    {
        $this->authorize('viewAny', MatterSheet::class);

        if ($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            if (Auth::user()->hasRole(['admin', 'super-admin'])) {
                $matterSheet = $matterSheet
                    ->where('title', 'like', '%' . $searchParam . '%')->orWhere('id', 'like', '%' . $searchParam . '%');
            } else {
                $matterSheet = $matterSheet->where('user_id', Auth::user()->id)
                    ->where('title', 'like', '%' . $searchParam . '%')->orWhere('id', 'like', '%' . $searchParam . '%')->and('user_id', Auth::user()->id);
            }
        }

        if (Auth::user()->hasRole(['admin', 'super-admin'])) {
            $matterSheet = $matterSheet->paginate($this->noOfItems);
        } else {
            $matterSheet = $matterSheet->where('user_id', Auth::user()->id)->paginate($this->noOfItems);
        }

        if ($request->ajax()) {
            return response()->json(['products' => $matterSheet, 'paginator' => (string)PaginatorTrait::getPaginator($request, $matterSheet)->links()]);
        } else {

            return view('pages.matter-sheet.listings.mattersheet.mattersheet-listing')->with([
                'products' => $matterSheet,
                'paginator' => (string)$matterSheet->links()
            ]);
        }
    }

    public function matterSheetApproval(MatterSheet $matterSheet, ProductImage $p_image)
    {
        $this->authorize('approval', MatterSheet::class);

        $message = __('Unable to save matter sheet details');
        $alertClass = 'alert-error';

        DB::beginTransaction();

        $matterSheet->is_cpa_approved = 1;
        if ($matterSheet->save()) {

            MatterSheetProduct::where('is_cpa_approved', 0)
                ->where('matter_sheet_id', $matterSheet->id)
                ->update(['is_cpa_approved' => 1]);

            $products = MatterSheetProduct::where('matter_sheet_id', $matterSheet->id)->get();

            foreach ($products as $product) {
                unset($product->is_cpa_approved);
                unset($product->matter_sheet_id);

                $imageProduct = $product->image_path;
                unset($product->image_path);

                $savedProduct = $product->replicate()->setTable('products')->save();

                $p_image->product_id = $savedProduct->id;
                $p_image->path = $imageProduct;
                $p_image->title = $product->title;
                $p_image->is_main = 1;

                $p_image->save();
            }

            DB::commit();
            Session::flash('message', __('Mattersheet has been saved successfully'));
            Session::flash('alert-class', 'alert-success');

            return redirect()->route('matter-sheet.matter_sheet_product.listing');
        } else {
            DB::rollBack();
            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return redirect()->back();
        }
    }

    public function destroyMatterSheet(MatterSheet $matter_sheet)
    {
        $this->authorize('destroyMatterSheet', MatterSheet::class);

        if ($matter_sheet->delete()) {
            Session::flash('message', __('Matter sheet has been deleted successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete matter sheet'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }


    public function companyPageSettings(BusinessDetail $businessDetail)
    {
        $businessDetails = $businessDetail::select('id')->where(['user_id' => Auth::user()->id])->get();

        if (!empty($businessDetails)) {
            $banner = Auth::user()->companyBanner;
            $bannerProducts = CompanyPageProduct::with(['product', 'product.images'])->where('user_id', Auth::user()->id)->where('display_section', 'banner')->get();
            $topProducts = CompanyPageProduct::with(['product', 'product.images'])->where('user_id', Auth::user()->id)->where('display_section', 'top_products')->get();
            $bannerProductsLimit = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'company_banner_products');
            $topProductsLimit = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'company_top_products');

            if (!empty($banner)) {
                $banner->banner_image_path = Storage::url($banner->banner_image_path);
            }

            return view('pages.profile.company-page')->with([
                'banner' => $banner,
                'banner_products' => $bannerProducts,
                'top_products' => $topProducts,
                'banner_products_limit' => $bannerProductsLimit,
                'top_products_limit' => $topProductsLimit
            ]);
        } else {
            Session::flash('message', __('Business details needs to be added before adding  details'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('matter-sheet.home');
        }
    }
}
