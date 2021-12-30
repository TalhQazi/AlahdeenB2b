<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductBuyRequirement as ResourcesProductBuyRequirement;
use App\Http\Resources\ProductBuyRequirementCollection;
use App\Models\ProductBuyRequirement;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use App\Traits\PaginatorTrait;
use Illuminate\Support\Facades\Auth;

class ProductBuyRequirementController extends Controller
{

    private $noOfItems;

    public function __construct()
    {
        $this->noOfItems = config('pagination.products_buy_requirements', config('pagination.default'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductBuyRequirement $productBuyRequirement, Request $request)
    {
      if($request->session()->get('user_type') == "seller") {
        $productBuyRequirement = $productBuyRequirement::with('buyer')->where('user_id','!=',Auth::user()->id);
      } else {
        $productBuyRequirement = $productBuyRequirement::with('buyer')->where('user_id', Auth::user()->id);
      }

        if ($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $productBuyRequirement = $productBuyRequirement->where(function ($query) use ($searchParam) {
                $query->where('required_product', 'like', '%' . $searchParam . '%')
                ->orWhere('id', 'like', '%' . $searchParam . '%');
            });
        }

        $userShortlistedLeads = $this->getUserShortlistedLeads()->pluck('favorable_id')->toArray();

        $isShortlisted = false;
        if ($request->get('f') == 'shortlisted') {
            $isShortlisted = true;
            $productBuyRequirement = $productBuyRequirement->whereIn('id', $userShortlistedLeads);
        }

        $productBuyRequirement = $productBuyRequirement->orderBy('created_at', 'desc');

        $productBuyRequirement = $productBuyRequirement->paginate($this->noOfItems);
        $productBuyRequirement = (new ProductBuyRequirementCollection($productBuyRequirement))->response()->getData();

        if ($request->ajax()) {
            return response()->json([
                    'products_buy_requirements' => $productBuyRequirement->data,
                    'paginator' => (string) PaginatorTrait::getPaginator($request, $productBuyRequirement)->links(),
                    'user_shortlisted_leads' => $userShortlistedLeads
                ]);
        } else {

            return view('pages.product-buy-requirements.index')->with([
              'products_buy_requirements' => $productBuyRequirement->data,
              'user_shortlisted_leads' => $userShortlistedLeads,
              'is_shortlisted' => $isShortlisted,
              'quantity_units' => config('quantity_unit'),
              'table_header' => 'components.product-buy-requirements.index.theader',
              'table_body' => 'components.product-buy-requirements.index.tbody',
              'paginator' => PaginatorTrait::getPaginator($request, $productBuyRequirement),
            ]);
        }
    }

    public function addRemoveshortlistLead(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:product_buy_requirements'
        ]);

        $lead = ProductBuyRequirement::find($request->id);

        $leadFavAlready = $this->getUserShortlistedLeads($lead->id);


        if (count($leadFavAlready) > 0) {
            // remove lead from shortlisted
            if (UserFavorite::destroy($leadFavAlready->first()->id)) {
                return response()->json([
                    'msg' => __('Lead removed from shortlisted leads successfully.'),
                    'status' => 204
                ]);
            }
        } else {
            // add lead to shortlisted
            $userFav = new UserFavorite(['user_id' => Auth::user()->id]);
            if ($lead->favorites()->save($userFav)) {
                return response()->json([
                    'msg' => __('Lead shortlisted successfully.'),
                    'status' => 201
                ]);
            }
        }

        return response()->json([
            'msg' => __('Oops, something went wrong'),
            'status' => 400
        ]);
    }

    protected function getUserShortlistedLeads($leadId = null)
    {
        $leadFavQuery = UserFavorite::where('favorable_type', ProductBuyRequirement::class);
        $leadFavQuery->where('user_id', Auth::user()->id);
        if ($leadId) {
            $leadFavQuery->where('favorable_id', $leadId);
        }

        return $leadFavQuery->get();
    }

    public function show(ProductBuyRequirement $productBuyRequirement, Request $request)
    {
      if($request->ajax()) {
        $productBuyRequirement = (new ResourcesProductBuyRequirement($productBuyRequirement))->response()->getData();
        return response()->json(
          $productBuyRequirement->data,
        );

      } else {
        return redirect()->route('dashboard');
      }
    }
}
