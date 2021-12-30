@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/create_invoice.css')) }}">
@endpush

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Create Invoice') }}</div>
        <div class="form-wrapper p-10">
            <form action="{{ route('khata.invoice.store') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="grid grid-cols-12 invoice-details">
                    <div class="col-span-5">
                        <div class="flex flex-col">

                            <div class="grid grid-cols-12">
                              <label class="col-span-3" for="invoice date">{{ __('Invoice Date') }}</label>
                              <input
                                  class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                name="invoice_date" id="invoice_date" type="text" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="paymentDueDate">{{ __('Payment Due Date') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    name="payment_due_date" id="payment_due_date" type="text" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="deliveryDate">{{ __('Delivery Date') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="delivery_date" id="delivery_date" autocomplete="off">
                            </div>

                        </div>
                    </div>
                    <div class="col-span-7">
                        <div class="flex flex-col">
                            <div class="grid grid-cols-4 gap-3">
                                <div class="invoice-logo">
                                  @if (!empty($company_logo))
                                  <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                    <div class="mb-4">
                                      <img class="w-auto mx-auto object-cover object-center h-20" id="logo_preview" src="{{ showPhoto(asset($company_logo)) }}" alt="Company Logo Upload" />
                                    </div>
                                  </div>
                                  @else
                                    <div class="invoice-attachment" id="po_company_logo">
                                      <button type="button" class="btn" id="po_company_logo_btn">
                                        <i class="fas fa-paperclip"></i>{{ __('Upload Company Logo') }}
                                      </button>
                                        <p class="text-xs">{{ __('Types Allowed: jpeg,jpg,png,pdf') }}</p>
                                        <p class="text-xs">{{ __('Max Size: 500Kb') }}</p>
                                      <input id="company_logo_input_file" name="purchase_order" type="file" class="hidden"/>
                                    </div>
                                  @endif
                                </div>
                                <div class="invoice-attachment" id="po_attachment">
                                  <button type="button" class="btn" id="add_purchase_order">
                                    <i class="fas fa-paperclip"></i>
                                    {{__('Attach Purchase Order')}}
                                  </button>
                                  <p class="text-xs">{{ __('Types Allowed: jpeg,jpg,png,pdf') }}</p>
                                  <p class="text-xs">{{ __('Max Size: 500Kb') }}</p>
                                <input id="purchase_order" name="purchase_order" type="file" class="hidden"/>
                                <div class="flex mt-2 hidden" id="purchase_order_div">
                                  <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                    <div class="mb-4">
                                        <img class="w-auto mx-auto object-cover object-center h-20"
                                            id="purchase_order_preview" src="{{ asset('img/camera_icon.png') }}"
                                            alt="Attach Purchase Order" />
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="invoice-attachment">
                                <button type="button" class="btn" id="add_delivery_challan">
                                  <i class="fas fa-paperclip"></i>
                                  {{__('Attach Delivery Challan')}}
                                </button>
                                <p class="text-xs">{{ __('Types Allowed: jpeg,jpg,png,pdf') }}</p>
                                <p class="text-xs">{{ __('Max Size: 500Kb') }}</p>
                                <input id="delivery_challan" name="delivery_challan" type="file" class="hidden"/>
                                <div class="flex mt-2 hidden" id="delivery_challan_div">
                                  <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                    <div class="mb-4">
                                        <img class="w-auto mx-auto object-cover object-center h-20"
                                            id="delivery_challan_preview" src="{{ asset('img/camera_icon.png') }}"
                                            alt="Attach Delivery Challan" />
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="invoice-attachment">
                                <button type="button" class="btn" id="add_shipment_receipt">
                                  <i class="fas fa-paperclip"></i>
                                  {{__('Attach Shipment Receipt')}}
                                </button>
                                <p class="text-xs">{{ __('Types Allowed: jpeg,jpg,png,pdf') }}</p>
                                <p class="text-xs">{{ __('Max Size: 500Kb') }}</p>
                                <input id="shipment_receipt" name="shipment_receipt" type="file" class="hidden"/>
                                <div class="flex mt-2 hidden" id="shipment_receipt_div">
                                  <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                    <div class="mb-4">
                                        <img class="w-auto mx-auto object-cover object-center h-20"
                                            id="shipment_receipt_preview" src="{{ asset('img/camera_icon.png') }}"
                                            alt="Attach Shipment Receipt" />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 mt-10">
                    <div class="col-span-5">
                        <div class="seller-details w-full flex">
                            <div id="billed-by"
                                class="flex flex-col border-2 border-gray-200 w-auto h-48 p-4 leading-relaxed">
                                <div class="title border-b border-gray-500">{{ __('Billed By') }} :
                                    {{ empty($user->business->company_name) ? $user->name : $user->business->company_name }}
                                </div>
                                <div class="seller-business-details">
                                    <div class="bd-title font-bold">{{ __('Business Details') }}</div>
                                    <div class="bd-li">
                                        <span class="li-label">{{ __('Address') }}</span>: <span
                                            class="li-value">{{ !empty($user->business) ? $user->business->address : $user->address }}</span>
                                    </div>
                                    <div class="bd-li">
                                        <span class="li-label">{{ __('Phone') }}</span>: <span
                                            class="li-value">{{ !empty($user->business) ? $user->business->phone_number : $user->phone_number }}</span>
                                    </div>
                                    @if(!empty($user->business))
                                        <div class="bd-li">
                                          <span class="li-label">{{ __('Web') }}</span>: <span
                                              class="li-value">{{ $user->business->alternate_website }}</span>
                                      </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-span-7">
                        <div class="buyer-details w-full flex">
                            <div id="billed-to"
                                class="flex flex-col border-2 border-gray-200 w-full h-48 p-4 leading-relaxed">
                                <div class="title border-b border-gray-500">
                                    @if (count($clients) > 0)
                                        <select name="client_id" id="client_id">
                                            <option value="">{{ __('Select Client') }}</option>
                                            @foreach ($clients as $cl)
                                                <option value="{{ $cl->id }}"
                                                    {{ !empty($client->client->id) && $cl->id == $client->client->id ? 'selected' : '' }}>
                                                    @if(!empty($cl->business))
                                                    {{ $cl->name }} --
                                                    {{ $cl->business->company_name }}</option>
                                                    @else
                                                    {{ $cl->name }}
                                                    @endif
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="buyer-business-details">
                                    <div class="bd-title font-bold">{{ __('Business Details') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-details w-full mt-10">
                  <div class="grid grid-cols-12">
                    <table class="table-auto border-collapse w-full col-span-12">
                      <thead>
                          <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                              <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('Sr.No') }}</th>
                              <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Item Details') }}</th>
                              <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Code') }}</th>
                              <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('QTY') }}</th>
                              <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Unit') }}</th>
                              <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Rate') }}</th>
                              <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('GST %') }}</th>
                              <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Amount Excl Tax') }}</th>
                              <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Tax Amount') }}</th>
                              <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Amount Incl Tax') }}</th>
                          </tr>
                      </thead>
                      <tbody class="text-sm font-normal text-gray-700" id="products_tbody">
                         <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                            <td class="px-4 py-4">
                              1
                            </td>
                            <td class="px-4 py-4">
                              <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 product_name" type="text" name="item[1][name]" id="item_name_1">
                              <input type="hidden" name="item[1][id]" id="item_id_1">
                              <input type="hidden" name="item[1][promotion_id]" id="item_promotion_id_1">
                            </td>
                            <td class="px-4 py-4">
                              <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40" type="text" name="item[1][code]" id="item_code_1">
                            </td>
                            <td class="px-4 py-4">
                              <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 qty" type="text" name="item[1][qty]" id="item_qty_1">
                            </td>
                            <td class="px-4 py-4">
                              <select name="item[1][unit]" id="item_unit_1" class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40">
                                <option value="">{{__('Select Unit')}}</option>
                                @foreach ($quantity_units as $unit)
                                  <option value="{{$unit}}">{{$unit}}</option>
                                @endforeach
                              </select>
                            </td>
                            <td class="px-4 py-4">
                              <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 rate" type="text" name="item[1][rate]" id="item_rate_1">
                            </td>
                            <td class="px-4 py-4">
                              <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 gst" type="text" name="item[1][gst]" id="item_gst_1" value="0">
                            </td>
                            <td class="px-4 py-4" id="item_tax_exc_amount_1">
                              0
                            </td>
                            <td class="px-4 py-4" id="item_tax_amount_1">
                              0
                            </td>
                            <td class="px-4 py-4" id="item_total_amount_1">
                              0
                            </td>
                         </tr>
                      </tbody>
                  </table>
                  <div class="flex col-span-12 justify-center mt-5">
                    <button class="btn btn-indigo" data-row-applied="0" id="apply_promotion_btn"><i class="fas fa-tag"></i>{{__(' Apply Promotions')}}</button>
                    <button class="btn btn-teal ml-3" id="add_more_btn"><i class="fas fa-plus-circle"></i>{{__(' Add More Products')}}</button>
                  </div>
                  </div>
                </div>
                <div class="invoice-bottom w-full">
                  <div class="grid grid-cols-12 w-full">
                    <div class="col-start-9 col-span-3 flex flex-col">
                      <div class="grid grid-cols-12">
                        <label class="col-span-4" for="Amount">{{ __('Amount') }}</label>
                        <input
                            class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-8"
                            name="amount" id="amount" type="text" value="0" readonly>
                      </div>
                      <div class="grid grid-cols-12">
                        <label class="col-span-4" for="Freight Charges">{{ __('Freight Charges') }}</label>
                        <input
                            class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-8"
                            name="freight_charges" id="freight_charges" type="text" value="0">
                      </div>
                      <div class="grid grid-cols-12">
                        <label class="col-span-4" for="Total Sales Tax">{{ __('Total Sales Tax') }}</label>
                        <input
                            class="justify-center text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-8"
                            name="total_sales_tax" id="total_sales_tax" type="text" value="0" readonly>
                      </div>
                      <hr class="mt-2">
                      <div class="grid grid-cols-12">
                        <label class="col-span-4" for="Total">{{ __('Total') }}</label>
                        <input
                            class="justify-center text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-8"
                            name="total" id="total" type="text" value="0" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="grid grid-cols-12 mt-5">
                    <div class="col-span-12">
                      <span id="add_terms_btn">
                        <i class="fas fa-plus-circle"></i>
                        {{__('Add new terms and conditions')}}
                      </span>
                      <textarea name="terms_and_conditions" id="terms_and_conditions" cols="30" rows="5" class="hidden appearance-none w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"></textarea>
                    </div>
                    <div class="col-span-12 mt-2">
                      <button type="button" class="btn" id="add_tax_certificate_btn">
                        <i class="fas fa-paperclip"></i>
                        {{__('Attach Income Tax Certificate (if applicable)')}}
                      </button>
                      <p class="text-xs">{{ __('Types Allowed: jpeg,jpg,png,pdf') }}</p>
                      <p class="text-xs">{{ __('Max Size: 500Kb') }}</p>
                      <input id="tax_certificate" name="tax_certificate" type="file" class="hidden"/>
                      <div class="flex mt-2 hidden" id="tax_certificate_div">
                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                          <div class="mb-4">
                              <img class="w-auto mx-auto object-cover object-center h-20"
                                  id="tax_certificate_preview" src="{{ asset('img/camera_icon.png') }}"
                                  alt="Attach Tax Certificate" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex col-span-12 justify-center mt-5">
                  <button class="btn" type="button" id="add_digital_signature_btn">
                    <i class="fas fa-plus-circle"></i>
                    {{__('Attach Digital Signature')}}
                    <i class="fas fa-paperclip"></i>
                  </button>
                  <p class="text-xs">{{ __('Types Allowed: jpeg,jpg,png') }}</p>
                  <p class="text-xs">{{ __('Max Size: 500Kb') }}</p>
                  <input id="digital_signature" name="digital_signature" type="file" class="hidden"/>
                </div>
                <div class="flex col-span-12 justify-center mt-5 hidden" id="digital_signature_div">
                  <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                    <div class="mb-4">
                        <img class="w-auto mx-auto object-cover object-center h-20"
                            id="digital_signature_preview" src="{{ asset('img/camera_icon.png') }}"
                            alt="Company Logo Upload" />
                    </div>
                  </div>
                </div>
                <div class="flex col-span-12 mt-10">
                  <label class="col-span-4" for="enquiry email">{{__('For any enquiry, reach out via email at')}}</label>
                  <input
                      class="justify-center text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-8"
                      name="contact_email" id="contact_email" type="email">
                  <label class="col-span-4" for="enquiry email">{{__('or call on')}}</label>
                  <input
                      class="justify-center text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-8"
                      name="contact_phone" id="contact_phone" type="text">
                </div>
                </div>
                <div class="flex col-span-12 justify-center mt-5">
                  <button class="btn btn-teal">{{__('Save & Continue')}}</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset(('/js/pages/manage_invoices.js')) }}"></script>
    @endpush

@endsection
