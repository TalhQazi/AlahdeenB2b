<html>
    <head>
        <style>
            table, th, td {
                border: 1px solid #ccc;
            }
            p {
                line-height: 20px;
            }
        </style>
    </head>
    <body>
        <div style="padding: 5px; background-color: #eee">
            <h3>{{!empty($data['seller_details']['company_name']) ? $data['seller_details']['company_name'] : Auth::user()->name}}</h3>
            <p>{!! $data['address'] !!}</p>
            <p>{!! $data['phone_full'] !!}</p>
            <p>{{$data['primary_email']}} @if(!empty($data['alternate_email'])) {{', ' . $data['alternate_email']}} @endif</p>
        </div>
        <div style="float:right;">
            <p>{{date('d/m/y')}}</p>
        </div>
        <div style="margin-top:50px;">
            <p>To</p>
            <p>{{$data['buyer_details']->name}}</p>
            @if(!@empty($data['buyer_details']->business))
              <p>{{$data['buyer_details']->business->company_name}}</p>
              <p>{!! $data['buyer_details']->business->address !!}</p>
            @endif
        </div>
        <div>
            <h3>{{__('Subject: Quotation for requirement')}}</h3>
            <h4>{{__('Dear'). ' '. $data['buyer_details']->name}}</h4>
            <p>{{'Ref: Your enquiry for requirement dated : ' . date('d/m/y') }}</p>
            <p>Thank you for showing interest in our products & contacting us. Please find our exclusive quotation for
                your requirement of products
            </p>
        </div>
        <div>
            <table style="border: 1px solid #ccc; width:100%;">
                <thead>
                    <tr>
                        <th>{{__('S.No')}}</th>
                        <th>{{__('Product Description')}}</th>
                        <th>{{__('Product Image')}}</th>
                        <th>{{__('Qty')}}</th>
                        <th>{{__('Price / Unit')}}</th>
                        <th>{{__('Total Amount')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subTotal = 0;
                        $grandTotal = 0;
                        $discount = !empty($data['discount']) ? $data['discount'] : 0;
                        $discountAmount = 0;
                    @endphp
                    @foreach ($data['product'] as $key => $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <p>{{$product}}</p>
                                <p>{!! $data['description'][$key] !!}</p>
                            </td>
                            @if(!empty($data['q_image_path'][$key]))
                            <td>
                                <img class="w-auto mx-auto object-cover object-center" src="{{public_path($data['q_image_path'][$key])}}" alt="Upload File" style="width: 160px; height: 160px;">
                            </td>
                            @else
                            <td></td>
                            @endif
                            @php
                                $productTotal = $data['price'][$key] * $data['quantity'][$key];
                                $subTotal += $productTotal;
                            @endphp
                            <td>{{$data['quantity'][$key]}}</td>
                            <td>{{'Rs ' . $data['price'][$key]. ' / ' . $data['unit'][$key]}}</td>
                            <td>{{'Rs ' .(string)($productTotal)}}</td>
                        </tr>
                    @endforeach
                    @php
                      $discountAmount =  $subTotal * ($discount / 100);
                      $grandTotal = $subTotal - $discountAmount;
                    @endphp
                    <tr>
                        <td colspan="5" style="text-align: right;">Sub Total:</td>
                        <td colspan="1">{{'Rs ' . $subTotal}}</td>
                    </tr>
                    @if ($discount != 0)
                    <tr>
                        <td colspan="5" style="text-align: right;">{{'Discount ' . '(' . $discount . '%):'}}</td>
                        <td colspan="1">{{'Rs ' . $discountAmount}}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="5" style="text-align: right;">Grand Total:</td>
                        <td colspan="1">{{'Rs ' . $grandTotal}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @if (!empty($data['payment_terms'] || !empty($data['period']) || !empty($data['additional_info'])))
        <div>
            <h4>{{__('Terms and Conditions')}}</h4>
            @if (!empty($data['period']))
                <p>{{'Delivery Period : ' . $data['period'] . ' ' . $data['period_unit']}}</p>
            @endif

            @if (!empty($data['payment_terms']))
                <p>{{'Payment Terms : ' . $data['payment_terms']}}</p>
            @endif

            @if (!empty($data['additional_info']))
                <p>{{'Additional Information : ' . $data['additional_info']}}</p>
            @endif
        </div>
        @endif
        <div>
            <p>Please contact us in case of any concerns.</p>
        </div>
        <div>
            <h4>{{__('Sincerely Yours')}}</h4>
            <p>{{Auth::user()->name}}</p>
            <p>{{$data['phone_full']}}</p>
        </div>
    </body>
</html>
