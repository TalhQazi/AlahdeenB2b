@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/manage_challan.css')) }}">
@endpush

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Create Delivery Challan') }}</div>
        <div class="form-wrapper p-10">
            <form action="{{ route('khata.challan.store') }}" enctype="multipart/form-data" id="challan_form" method="post">
                @csrf
                <input type="hidden" name="send_via_chatbox" id="send_via_chatbox" value="0">
                <div class="grid grid-cols-12 challan-detail">
                    <div class="col-span-5">
                        <div class="flex flex-col">
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="from">{{ __('From') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    name="from" id="from" type="text" value="{{!empty($user->business) ? $user->name.'/'.$user->business->company_name : $user->name }}">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="to">{{ __('To') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    name="to" id="to" type="text">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3"
                                    for="purchase order delivery date">{{ __('Challan Date') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="challan_date" id="challan_date" placeholder="YYYY-mm-dd" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="items included">{{ __('Items Included') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="items_included" id="items_included" placeholder="" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="no_of_pieces">{{ __('No of Pieces') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="no_of_pieces" id="no_of_pieces" placeholder="{{__('Include approx. pieces in the shipment')}}" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="approx weight">{{ __('Approx Weight') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="weight" id="weight" placeholder="{{__('Approx weight of the shipment')}}" autocomplete="off">
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
                                                <option data-client-name="{{$cl->name}}" value="{{ $cl->id }}">
                                                    @if ($cl->business)
                                                    {{ $cl->name }} -- {{ $cl->business->company_name }}
                                                    @else
                                                      {{ $cl->name }}
                                                    @endif
                                            @endforeach
                                    </select>
                                </div>
                                <div class="invoice-logo">
                                    @if (!empty($company_logo))
                                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                            <div class="mb-4">
                                                <img class="w-auto mx-auto object-cover object-center h-20"
                                                    id="logo_preview" src="{{ url($company_logo) }}"
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
                                    type="text" name="bilty_no" id="bilty_no" placeholder="{{__('No. of Delievery Receipt')}}" autocomplete="off">
                            </div>
                            <div class="grid grid-cols-12">
                                <label class="col-span-3" for="approx weight">{{ __('Name of Courier') }}</label>
                                <input
                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 col-span-6"
                                    type="text" name="courier_name" id="courier_name"
                                    placeholder="{{__('Name of Courier/Logistics Company')}}" autocomplete="off">
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
                <div class="flex col-span-12 justify-center mt-5 hidden" id="digital_signature_div">
                  <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                    <div class="mb-4">
                        <img class="w-auto mx-auto object-cover object-center h-20"
                            id="digital_signature_preview" src="{{ asset('img/camera_icon.png') }}"
                            alt="Company Logo Upload" />
                    </div>
                  </div>
                </div>
                <div class="flex col-span-12 justify-center mt-5">
                    <button class="btn btn-teal">{{ __('Save') }}</button>
                    <button type="button" class="ml-3 btn btn-gray" id="send_btn">{{ __('Send via chatbox') }}</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/pages/manage_challan.js') }}"></script>
    @endpush

@endsection
