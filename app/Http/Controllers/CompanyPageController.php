<?php

namespace App\Http\Controllers;

use App\Models\BusinessDetail;
use App\Models\CompanyPageProduct;
use App\Traits\PackageUsageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CompanyPageController extends Controller
{
    use PackageUsageTrait;

    public function index(BusinessDetail $businessDetail)
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
