@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
@endpush


@section('page')
    @parent

    <div class="min-h-screen pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6">
                <h2 class="text-2xl text-gray-900">My Products & Services</h2>
                <form id="add_product_service_form" class="mt-6 border-t border-gray-400 pt-4" method="POST" action="{{route('product.services.store')}}">
                    @csrf

                    <div class='flex flex-wrap -mx-3 mb-6'>

                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Select Primary Business Type</label>
                            <div class="flex-shrink w-full inline-block relative">
                                <?php
                                    $businessTypeId = '';
                                    $mainProducts = [];
                                    $productServiceId = '';
                                    if(!empty($primary_product_services)) {
                                        $productServiceId = $primary_product_services[0]->id;
                                        $businessTypeId = $primary_product_services[0]->business_type_id;
                                        $mainProducts = json_decode($primary_product_services[0]->keywords);
                                    }
                                ?>
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="primary_business_type">
                                    <option value="">select</option>
                                    @foreach ($business_types as $type)
                                        <option value="{{$type->id}}" <?php echo ($type->id === $businessTypeId) ? 'selected' : '' ?>>{{$type->business_type}}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Main Products</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 keywords' data-target-div='#main_products' name="main_keywords_input" id='main_keywords_input' type='text'>
                        </div>

                        <div id="main_products" class='w-full md:w-full px-3 mb-6'>
                            @if (!empty($mainProducts))
                                @foreach ($mainProducts as $product)
                                    <?php
                                        $spanId = preg_replace('/\W/','_', strtolower($product));
                                    ?>
                                    <span id='{{$spanId}}' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>
                                        {{$product}}
                                        <i class='fa fa-times ml-2 delete-keyword existing-keyword' data-prodservice-id='{{$productServiceId}}' data-keyword="{{$product}}" data-target-parent="{{$product}}"></i>
                                        <input type='hidden' name='main_keywords[]' value='{{$product}}'>
                                    </span>
                                @endforeach
                            @endif
                        </div>

                        {{-- <h2>Secondary Business(es)</h2> --}}

                        <?php
                            $businessTypeIds = [];
                            $secondaryProducts = [];
                            // $secondaryProdServIds = [];
                            if(!empty($secondary_product_services)) {
                                foreach ($secondary_product_services as $key => $productService) {
                                    array_push($businessTypeIds,$productService->business_type_id);
                                    // array_push($secondaryProdServIds,$productService->id);
                                    // $secondaryProdServIds[$productService->id] = $productService->id;
                                    $seondaryProducts[$productService->business_type_id]['id'] = $productService->id;
                                    $seondaryProducts[$productService->business_type_id]['keywords'] = json_decode($productService->keywords);
                                }
                                // print_r($secondaryProdServIds);
                            }
                        ?>

                        @foreach ($business_types as $type)
                        <div class="grid grid-row-2 w-full px-3 mb-6">

                            <div class='grid grid-cols-12 w-full'>
                                <label class='col-span-3 xs:col-span-7 sm:col-span-7 uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{$type->business_type}}</label>
                                @if (!empty($businessTypeIds) && in_array($type->id,$businessTypeIds))
                                    <input type="checkbox" name="secondary_business_types[{{$type->id}}]" class="col-span-9 xs:col-span-5 sm:col-span-5 secondary_business" data-business-target="{{$type->id}}" checked>
                                @else
                                    <input type="checkbox" name="secondary_business_types[{{$type->id}}]" class="col-span-9 xs:col-span-5 sm:col-span-5 secondary_business" data-business-target="{{$type->id}}">
                                @endif
                            </div>

                            @if (!empty($businessTypeIds) && in_array($type->id,$businessTypeIds))
                                <div class="w-full grid grid-cols-12 mt-5 mb-5" id="{{'keywords_input_'.$type->id}}">
                                    <label class='uppercase tracking-wide text-gray-700 text-xs font-bold col-span-3 sm:col-span-12 xs:col-span-12 leading-10 align-middle'>Keywords</label>
                                    <input class='appearance-none w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500 col-span-9 sm:col-span-12 xs:col-span-12 keywords' name="secondary_keywords_input" id='{{strtolower($type->business_type)."_keywords"}}' data-target-div='{{'#secondary_products_'.$type->id}}' type='text'>
                                </div>
                            @else
                                <div class="w-full grid grid-cols-12 mt-5 mb-5 hidden" id="{{'keywords_input_'.$type->id}}">
                                    <label class='uppercase tracking-wide text-gray-700 text-xs font-bold col-span-3 sm:col-span-12 xs:col-span-12 leading-10 align-middle'>Keywords</label>
                                    <input class='appearance-none w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500 col-span-9 sm:col-span-12 xs:col-span-12 keywords' name="secondary_keywords_input" id='{{strtolower($type->business_type)."_keywords"}}' data-target-div='{{'#secondary_products_'.$type->id}}' type='text'>
                                </div>
                            @endif


                            <div id="{{'secondary_products_'.$type->id}}" class='w-full md:w-full px-3 mb-6'>
                                @if(!empty($seondaryProducts) && !empty($seondaryProducts[$type->id]))
                                    @foreach ($seondaryProducts[$type->id]['keywords'] as $key=> $product)
                                        <?php
                                            $spanId = $type->id.'_'.preg_replace('/\W/','_', strtolower($product));
                                        ?>
                                        <span id='{{$spanId}}' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>
                                            {{$product}}
                                        <i class='fa fa-times ml-2 delete-keyword existing-keyword' data-prodservice-id='{{$seondaryProducts[$type->id]["id"]}}' data-keyword="{{$product}}" data-target-parent="{{$type->id . ' ' . $product}}"></i>
                                            <input type='hidden' name='secondary_keywords[{{$type->id}}][]' value='{{$product}}'>
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endforeach
                        <div class="personal w-full border-t border-gray-400 pt-4">
                            <div class="flex justify-end">
                                <button class="border border-gray-700 bg-gray-700 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('modals')
    @parent
    @include('components.product_services.delete-modal')
@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/product_services.js')) }}"></script>
@endpush

