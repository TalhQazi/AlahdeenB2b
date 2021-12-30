@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/manage_challan.css')) }}">
@endpush

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Edit Delivery Challan') }}</div>
        <div class="form-wrapper p-10">
            <form action="{{ route('khata.challan.update', ['challan' => $challanInfo->id]) }}" enctype="multipart/form-data" id="challan_form" method="post">
                @method('PUT')
                @csrf
                <div class="grid grid-cols-12 challan-detail">
                    <div class="col-span-5">
                        <div class="flex flex-col">
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="from">{{ __('From') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    name="from" id="from" type="text" value="{{!empty($seller_details->business) ? $seller_details->name.'/'.$seller_details->business->company_name : $seller_details->name }}">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="to">{{ __('To') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    name="to" id="to" type="text" value="{{!empty($buyer_details->business) ? $buyer_details->name.'/'.$buyer_details->business->company_name : $buyer_details->name }}">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3"
                                    for="purchase order delivery date">{{ __('Challan Date') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="challan_date" id="challan_date" placeholder="YYYY-mm-dd" value="{{ $challanInfo->challan_date }}" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="items included">{{ __('Items Included') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="items_included" id="items_included" placeholder="" value="{{ $challanInfo->items_included }}" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="no_of_pieces">{{ __('No of Pieces') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="no_of_pieces" id="no_of_pieces" placeholder="{{__('Include approx. pieces in the shipment')}}" value="{{$challanInfo->no_of_pieces}}" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="approx weight">{{ __('Approx Weight') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="weight" id="weight" placeholder="{{__('Approx weight of the shipment')}}" value="{{$challanInfo->weight}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-span-7">
                        <div class="flex flex-col">
                            <div class="grid grid-cols-2 gap-1">
                                <div class="grid grid-cols-12">
                                    <label class="col-span-3" for="approx weight">{{ __('Billed To ') }}</label>
                                    <select id="client_id" name="client_id"
                                        class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-64 h-8">
                                          <option value="">{{ __('Select Client') }}</option>
                                            @foreach ($clients as $cl)
                                                <option data-client-name="{{$cl->name}}" value="{{ $cl->id }}" @if($cl->id == $buyer_details->id) selected @endif>
                                                    @if ($cl->business)
                                                    {{ $cl->name }} -- {{ $cl->business->company_name }}
                                                    @else
                                                      {{ $cl->name }}
                                                    @endif
                                            @endforeach
                                    </select>
                                </div>
                                <div class="invoice-logo">
                                    @if (!empty($seller_details->business) && !empty($seller_details->business->additionalDetails))
                                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                            <div class="mb-4">
                                                <img class="w-auto mx-auto object-cover object-center h-20"
                                                    id="logo_preview" src="{{ url($seller_details->business->additionalDetails->logo) }}"
                                                    alt="Company Logo Upload" />
                                            </div>
                                        </div>
                                    @else
                                        <div id="po_company_logo"><i
                                                class="fas fa-paperclip"></i>{{ __('Upload Company Logo') }}</div>
                                        <input type="file" class="hidden" name="logo_path" id="logo_path" value="">
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-12 mt-10">
                                <label class="col-span-3" for="builty no">{{ __('Bilty No.') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="bilty_no" id="bilty_no" placeholder="{{__('No. of Delievery Receipt')}}" value="{{ $challanInfo->bilty_no }}" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="approx weight">{{ __('Name of Courier') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="courier_name" id="courier_name"
                                    placeholder="{{__('Name of Courier/Logistics Company')}}" value="{{$challanInfo->courier_name}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex col-span-12 justify-center mt-5">
                  <span id="add_digital_signature_btn">
                    <i class="fas fa-plus-circle"></i>
                    {{__('Attach Digital Signature')}}
                    <i class="fas fa-paperclip"></i>
                  </span>
                  <input id="digital_signature" name="digital_signature" type="file" class="hidden"/>
                </div>

                <?php
                  $hiddenClass = 'hidden';
                  $signaturePath = asset('img/camera_icon.png');
                ?>
                @if(!empty($challanInfo->digital_signature))
                  <?php
                    $hiddenClass = '';
                    $signaturePath = asset(str_replace('/storage/','',$challanInfo->digital_signature));
                  ?>
                @endif
                <div class="flex col-span-12 justify-center mt-5 {{$hiddenClass}}" id="digital_signature_div">
                  <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                    <div class="mb-4">
                        <img class="w-auto mx-auto object-cover object-center h-20"
                            id="digital_signature_preview" src="{{ $signaturePath }}"
                            alt="Digital Signature" />
                    </div>
                  </div>
                </div>
                <div class="flex col-span-12 justify-center mt-5">
                    <button class="btn btn-teal">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset(('/js/pages/manage_challan.js')) }}"></script>
    @endpush

@endsection
