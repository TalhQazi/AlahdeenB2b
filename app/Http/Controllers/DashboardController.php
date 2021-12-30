<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Models\QuotationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $analyticsDuration = config('global.month_duration', 3);
        $tomorrowMidnight = Carbon::parse('tomorrow midnight')->format('Y-m-d H:i:s');

        $monthLabels = [];
        for($i = 0; $i <= ($analyticsDuration - 1); $i++) {
            $monthLabels[$i] = Carbon::today()->subMonths($i)->format('F');
        }

        $reqForQuotationTrends = $this->requestForQuotationStats($analyticsDuration, $tomorrowMidnight);
        $quotationTrends = $this->quotationStats($analyticsDuration, $tomorrowMidnight);

        if(Session::get('can_view_buying_selling_analytics')) {

            $invoices['sent'] = Invoice::with(['items'])
                                ->where('seller_id', Auth::user()->id)
                                ->whereBetween('is_shared_date', [Carbon::today()->subMonths($analyticsDuration), $tomorrowMidnight])
                                ->orderBy('is_shared_date')->get();
            $invoices['received'] = Invoice::with(['items'])
                                    ->where('buyer_id', Auth::user()->id)
                                    ->whereBetween('is_shared_date', [Carbon::today()->subMonths($analyticsDuration), $tomorrowMidnight])
                                    ->orderBy('is_shared_date')->get();

            $purchaseOrders = PurchaseOrder::where('created_by', Auth::user()->id)
                                ->whereBetween('created_at', [Carbon::today()->subMonths($analyticsDuration), $tomorrowMidnight])
                                ->orderBy('created_at')->get();

            $trendingData['buying'] = $trendingData['selling'] = [];
            $totalDivision['buying'] = $totalDivision['selling'] = [
                'grand_total' => 0
            ];


            $trendingData['buying'] = $this->trendsAndDivision($invoices['received']);
            $totalDivision['buying']['grand_total'] = !empty($trendingData['buying']['grand_total']) ? $trendingData['buying']['grand_total'] : 0;
            unset($trendingData['buying']['grand_total']);
            $trendingData['selling'] = $this->trendsAndDivision($invoices['sent']);
            $totalDivision['selling']['grand_total'] = !empty($trendingData['selling']['grand_total']) ? $trendingData['selling']['grand_total'] : 0;
            unset($trendingData['selling']['grand_total']);


            $purchaseOrderTrends = [];
            foreach($purchaseOrders as $purchaseOrder) {
                $month = Carbon::parse($purchaseOrder->created_at)->format('F');
                if(!array_key_exists($month, $purchaseOrderTrends)) {
                    $purchaseOrderTrends[$month] = 1;
                } else {
                    $purchaseOrderTrends[$month] += 1;
                }
            }

            return view('pages.dashboard.index')->with(
                [
                    'trends' => [
                        'buying' => $trendingData['buying'],
                        'selling' => $trendingData['selling'],
                        'months' => array_reverse($monthLabels)
                    ],
                    'total_division' => [
                        'buying' => $totalDivision['buying'],
                        'selling' => $totalDivision['selling'],
                        'grand_total' => $totalDivision['buying']['grand_total'] + $totalDivision['selling']['grand_total']
                    ],
                    'buying_trends' => [
                        'trends' => $purchaseOrderTrends,
                        'months' => array_reverse($monthLabels)
                    ],
                    'req_for_quotation_trends' => [
                        'received' => $reqForQuotationTrends['received'],
                        'sent' => $reqForQuotationTrends['sent'],
                        'months' => array_reverse($monthLabels)
                    ],
                    'quotation_trends' => [
                        'received' => $quotationTrends['received'],
                        'sent' => $quotationTrends['sent'],
                        'months' => array_reverse($monthLabels)
                    ]

                ]
            );
        } else {



            return view('pages.dashboard.index')->with([
                'trends' => [],
                'total_division' => [],
                'buying_trends' => [],
                'req_for_quotation_trends' => [
                    'received' => $reqForQuotationTrends['received'],
                    'sent' => $reqForQuotationTrends['sent'],
                    'months' => array_reverse($monthLabels)
                ],
                'quotation_trends' => [
                    'received' => $quotationTrends['received'],
                    'sent' => $quotationTrends['sent'],
                    'months' => array_reverse($monthLabels)
                ]
            ]);
        }
    }

    public function trendsAndDivision($invoices) {
        $trendingData = [
            'grand_total' => 0
        ];
        if(!empty($invoices)) {

            foreach($invoices as $invoice) {
                $month = Carbon::parse($invoice->is_shared_date)->format('F');
                if(!array_key_exists($month, $trendingData)) {
                    $trendingData[$month] = 1;
                } else {
                    $trendingData[$month] += 1;
                }

                $productsTotal = 0;
                $grandTotal = 0;
                $excTaxProductTotal = 0;
                $taxAmountProductTotal = 0;
                $taxInclProductTotal = 0;
                $salesTax = 0;

                foreach($invoice->items as $product) {
                    $excTaxProductTotal = $product->rate * $product->quantity;
                    $taxAmountProductTotal = $excTaxProductTotal * ( $product->gst  / 100 );
                    $taxInclProductTotal = $excTaxProductTotal + $taxAmountProductTotal;
                    $productsTotal += $taxInclProductTotal;
                    $salesTax += $taxAmountProductTotal;
                }

                $grandTotal = $productsTotal + $invoice->freight_charges + $salesTax;
                $trendingData['grand_total'] += $grandTotal;
            }
        }

        return $trendingData;
    }

    public function requestForQuotationStats($analyticsDuration, $tomorrowMidnight)
    {
        $requestForQuotations['received'] = QuotationRequest::where('seller_id', Auth::user()->id)
                                                            ->whereBetween('created_at', [Carbon::today()->subMonths($analyticsDuration), $tomorrowMidnight])
                                                            ->get();
        $requestForQuotations['sent'] = QuotationRequest::where('buyer_id', Auth::user()->id)
                                                        ->whereBetween('created_at', [Carbon::today()->subMonths($analyticsDuration), $tomorrowMidnight])
                                                        ->get();

        $reqForQuotationTrends['received'] = [];
        $reqForQuotationTrends['sent'] = [];

        foreach($requestForQuotations['received'] as $reqForQuotation) {
            $month = Carbon::parse($reqForQuotation->created_at)->format('F');
            if(!array_key_exists($month, $reqForQuotationTrends['received'])) {
                $reqForQuotationTrends['received'][$month] = 1;
            } else {
                $reqForQuotationTrends['received'][$month] += 1;
            }
        }

        foreach($requestForQuotations['sent'] as $reqForQuotation) {
            $month = Carbon::parse($reqForQuotation->created_at)->format('F');
            if(!array_key_exists($month, $reqForQuotationTrends['sent'])) {
                $reqForQuotationTrends['sent'][$month] = 1;
            } else {
                $reqForQuotationTrends['sent'][$month] += 1;
            }
        }


        return $reqForQuotationTrends;
    }

    public function quotationStats($analyticsDuration, $tomorrowMidnight)
    {
        $quotations['received'] = Quotation::where('buyer_id', Auth::user()->id)
                                                            ->whereBetween('created_at', [Carbon::today()->subMonths($analyticsDuration), $tomorrowMidnight])
                                                            ->get();
        $quotations['sent'] = Quotation::where('seller_id', Auth::user()->id)
                                                        ->whereBetween('created_at', [Carbon::today()->subMonths($analyticsDuration), $tomorrowMidnight])
                                                        ->get();

        $quotationTrends['received'] = [];
        $quotationTrends['sent'] = [];

        foreach($quotations['received'] as $quotation) {
            $month = Carbon::parse($quotation->created_at)->format('F');
            if(!array_key_exists($month, $quotationTrends['received'])) {
                $quotationTrends['received'][$month] = 1;
            } else {
                $quotationTrends['received'][$month] += 1;
            }
        }

        foreach($quotations['sent'] as $quotation) {
            $month = Carbon::parse($quotation->created_at)->format('F');
            if(!array_key_exists($month, $quotationTrends['sent'])) {
                $quotationTrends['sent'][$month] = 1;
            } else {
                $quotationTrends['sent'][$month] += 1;
            }
        }


        return $quotationTrends;
    }
}
