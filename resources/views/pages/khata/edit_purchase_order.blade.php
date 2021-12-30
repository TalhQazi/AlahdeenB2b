@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/manage_purchase_order.css')) }}">
@endpush

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Edit Purchase Order') }}</div>
        <div class="form-wrapper p-10">
            <form action="{{ route('khata.purchase-order.update', ['purchase_order' => $purchase_order->id]) }}" enctype="multipart/form-data" id="purchase_order_form" method="post">
                @method('PUT')
                @csrf
                <div class="grid grid-cols-12 po-details">
                    <div class="col-span-5">
                        <div class="flex flex-col">
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="purchase order number">{{ __('PO Number') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    name="po_number" id="po_number" type="text" value="{{$purchase_order->number}}" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                              <label class="col-span-3" for="purchase order date">{{ __('PO Date') }}</label>
                              <input
                                  class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                  name="po_date" id="po_date" type="text" placeholder="YYYY-mm-dd" value="{{$purchase_order->po_date}}" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="purchase order delivery date">{{ __('PO Delivery Date') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="po_delivery_date" id="po_delivery_date" placeholder="YYYY-mm-dd HH:mm" value="{{$purchase_order->po_delivery_date}}"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-span-7">
                        <div class="flex flex-col">
                            <div class="grid grid-cols-2 gap-1">
                                <div class="invoice-logo">
                                  @if (!empty($company_logo))
                                  <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                    <div class="mb-4">
                                      <img class="w-auto mx-auto object-cover object-center h-20" id="logo_preview" src="{{ url($company_logo) }}" alt="Company Logo Upload" />
                                    </div>
                                  </div>
                                  @else
                                    <div id="po_company_logo"><i class="fas fa-paperclip"></i>{{ __('Upload Company Logo') }}</div>
                                    <input type="file" class="hidden" name="logo_path" id="logo_path" value="">
                                  @endif
                                </div>
                                <div class="attachment">
                                  <span id="po_attachment">
                                    <i class="fas fa-paperclip"></i>
                                    {{__(' Attach Relevant File/Image')}}
                                  </span>
                                  <input id="attachment" name="attachment" type="file" class="hidden"/>
                                  <div class="flex mt-5 {{ $purchase_order->attachmentClass }}" id="attachment_div">
                                    <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                      <div class="mb-4">
                                        @if($purchase_order->attachmentType == 'pdf')
                                          <a target="_blank" href="{{url(Storage::url($purchase_order->attachment))}}">
                                            <img class="w-auto mx-auto object-cover object-center h-20"
                                            id="attachment_preview" src="{{ $purchase_order->attachmentUrl }}"
                                          />
                                          </a>
                                        @else
                                          <img class="w-auto mx-auto object-cover object-center h-20"
                                              id="attachment_preview" src="{{ $purchase_order->attachmentUrl }}"
                                          />
                                        @endif
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 mt-10">
                    <div class="col-span-7">
                        <div class="product-details w-full flex flex-col">
                          <label class="col-span-3" for="purchase order details">{{ __('PO Details') }}</label>
                          <textarea name="order_description" id="order_description" placeholder="{{__('Products their details along with their quantity')}}" cols="30" rows="5" class="appearance-none w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500">{{$purchase_order->order_description}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 mt-10">
                  <div class="col-span-7">
                    <div class="product-details w-full flex flex-col">
                      <label class="col-span-3" for="payment details">{{ __('Payment Details') }}</label>
                      <textarea name="payment_details" id="payment_details" cols="30" rows="5" class="appearance-none w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500">{{$purchase_order->payment_details}}</textarea>
                    </div>
                  </div>
              </div>



                <div class="flex col-span-12 justify-center mt-5">
                  <button class="btn btn-teal">{{__('Update')}}</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset(('js/pages/manage_purchase_order.js')) }}"></script>
    @endpush

@endsection
