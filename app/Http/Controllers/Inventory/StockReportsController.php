<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockReportsController extends Controller
{
    private $interval;

    public function __construct()
    {
        $this->interval = config("inventory_stock.sales_interval", 30);
    }

    public function largestDealsDone(Request $request)
    {

        $endDate = $request->query('end_date', Carbon::parse('tomorrow midnight')->format('Y-m-d H:i:s'));
        $startDate = $request->query('start_date', Carbon::today()->subDays($this->interval));

        $productWithDefinitions = Product::select(
                            'bd.company_name',
                            'products.title',
                            'ii.quantity'
                        )
                        ->join('invoice_items as ii', 'products.id', '=', 'ii.product_id')
                        ->join('invoices as i', 'ii.invoice_id', '=', 'i.id')
                        ->join('inventory_product_definitions as ipd', 'products.id', '=', 'ipd.product_id')
                        ->join('inventory_product_pricings as ipp', 'products.id', '=', 'ipp.product_id')
                        ->join('users as u', 'i.seller_id', '=', 'u.id')
                        ->join('business_details as bd', 'u.id', '=', 'bd.user_id')
                        ->where('products.user_id', Auth::user()->id)
                        ->whereNotNull('ipd.id')
                        ->whereBetween('ii.created_at', [$startDate, $endDate])
                        ->orderBy('ii.quantity', 'desc')
                        ->limit(3)
                        ->offset(0)
                        ->get();

        return $productWithDefinitions;
    }

    public function stockReports(Request $request)
    {

        Validator::make($request->query(), [
            'start_date' => ["required", "date", "date_format:Y-m-d H:i:s"],
            'end_date' => ["required", "date", "date_format:Y-m-d H:i:s", "after:".$request->start_date],
        ])->validate();

        $result = $this->stockReportsQuery($request);

        $mostRevenueGenerating = [];

        if(!empty($result)) {

            $mostRevenueGenerating['total_revenue'] = $result->sum('total_sales');
            $mostRevenueGenerating['products']['title'] = [];
            $mostRevenueGenerating['products']['contribution'] = [];
            $mostRevenueGeneratingProducts = $result->sortByDesc('total_sales')->values()->slice(0,3);
            $mostRevenueGeneratingProducts = $mostRevenueGeneratingProducts->each(function($item, $key) use (&$mostRevenueGenerating){
                $contribution = round(($item->total_sales / $mostRevenueGenerating['total_revenue']) * 100, 2);
                array_push($mostRevenueGenerating['products']['title'], $item->title);
                array_push($mostRevenueGenerating['products']['contribution'], $contribution.'%');
            });

        }

        $largestDealsDone = $this->largestDealsDone($request);

        return response()->json([
            'most_revenue_generating' => $mostRevenueGenerating,
            'largest_deals_done' => $largestDealsDone
        ]);
    }


    public function stockReportsQuery(Request $request)
    {
        $endDate = $request->query('end_date', Carbon::parse('tomorrow midnight')->format('Y-m-d H:i:s'));
        $startDate = $request->query('start_date', Carbon::today()->subDays($this->interval));
        $productWithDefinitions = Product::select(
                            'products.id',
                            'products.title',
                            DB::raw('sum(ii.quantity) as total_sold_units'),
                            DB::raw('sum(ii.quantity * ii.rate + ( (ii.quantity * ii.rate) * (ii.gst / 100) ) ) as total_sales'),

                        )
                        ->leftJoin('inventory_product_definitions as ipd', 'products.id', '=', 'ipd.product_id')
                        ->leftJoin('inventory_product_pricings as ipp', 'products.id', '=', 'ipp.product_id')
                        ->leftJoin('invoice_items as ii', 'products.id', '=', 'ii.product_id')
                        ->where('products.user_id', Auth::user()->id)
                        ->whereNotNull('ipd.id')
                        ->whereBetween('ii.created_at', [$startDate, $endDate])
                        ->groupBy('products.id')
                        ->get();


        return $productWithDefinitions;

    }

    public function salesRecords(Request $request)
    {

        Validator::make($request->query(), [
            'start_date' => ["required", "date", "date_format:Y-m-d H:i:s"],
            'end_date' => ["required", "date", "date_format:Y-m-d H:i:s", "after:".$request->start_date],
        ])->validate();

        $result = $this->salesRecordQuery($request);

        $mostQtySold = [];
        $mostSales = [];
        $mostSoldProducts = [];
        $risingProducts = [];
        $underPerformingProducts = [];

        if(!empty($result)) {

            $mostQtySold = $result->sortByDesc('total_sold_units')->values()->slice(0,5);
            $mostSales = $result->sortByDesc('total_sales')->values()->slice(0,5);

            $mostSoldProducts = $result->sortBy([
                fn($a, $b) => $b['total_sales'] <=> $a['total_sales'],
                fn($a, $b) => $b['total_sold_units'] <=> $a['total_sold_units'],
            ])->slice(0,5);

            $risingProducts = $result->sortBy([
                fn($a, $b) => $b['total_sales'] <=> $a['total_sales'],
                fn($a, $b) => $a['total_sold_units'] <=> $b['total_sold_units'],
            ])->slice(0,5);

            $underPerformingProducts = $result->sortBy([
                fn($a, $b) => $b['total_sold_units'] <=> $a['total_sold_units'],
                fn($a, $b) => $a['total_sales'] <=> $b['total_sales'],
            ])->slice(0,5);
        }

        return response()->json([
            'qty_sales_record' => $mostQtySold,
            'value_sales_record' => $mostSales,
            'most_sold_products_record' => $mostSoldProducts,
            'rising_products_record' => $risingProducts,
            'under_performing' => $underPerformingProducts
        ]);
    }

    public function salesRecordQuery(Request $request)
    {
        $endDate = $request->query('end_date', Carbon::parse('tomorrow midnight')->format('Y-m-d H:i:s'));
        $startDate = $request->query('start_date', Carbon::today()->subDays($this->interval));
        $productWithDefinitions = Product::select(
                            'products.id',
                            'products.title',
                            DB::raw('sum(ii.quantity) as total_sold_units'),
                            DB::raw('sum(ii.quantity * ii.rate + ( (ii.quantity * ii.rate) * (ii.gst / 100) ) ) as total_sales'),
                        )
                        ->leftJoin('inventory_product_definitions as ipd', 'products.id', '=', 'ipd.product_id')
                        ->leftJoin('inventory_product_pricings as ipp', 'products.id', '=', 'ipp.product_id')
                        ->leftJoin('invoice_items as ii', 'products.id', '=', 'ii.product_id')
                        ->where('products.user_id', Auth::user()->id)
                        ->whereNotNull('ipd.id')
                        ->whereBetween('ii.created_at', [$startDate, $endDate])
                        ->groupBy('products.id')
                        ->get();


        return $productWithDefinitions;

    }

}
