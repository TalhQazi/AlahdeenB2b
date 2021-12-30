<html>
    <head>
        <style>
            p {
                line-height: 5px;
            }
            table#products {
                border: 1px solid #ccc;
            }
            table#products th {
                border: 1px solid #ccc;
            }

            table#products td {
                border: 1px solid #ccc;
            }

        </style>
    </head>
    <body>
        @if( !empty($data['seller']->business) && !empty($data['seller']->business->additionalDetails) && !empty($data['seller']->business->additionalDetails->logo) )
        <div style="padding: 5px;">
          <img width="100px" height="100px" src="{{public_path(Storage::url($data['seller']->business->additionalDetails->logo))}}">
        </div>
        @endif
        <div style="padding: 5px; width: 100%;">
            <p>Invoice Number: {{ $data['invoice']->number }}</p>
            <p>Invoice Date: {{ $data['invoice']->invoice_date }}</p>
            <p>Payment Due Date: {{ $data['invoice']->payment_due_date }}</p>
            <p>Delivery Date: {{ $data['invoice']->delivery_date }}</p>
        </div>
        <div style="padding: 5px; display:inline-block; width: 49%; float:left; min-height: 100px; overflow:hidden;">
          <p><b>Billed By: {{ $data['invoice']->seller_details->company_name }}</b></p>
          <p>Business Details</p>
          <p>Address: {{ $data['invoice']->seller_details->address }}</p>
          <p>Phone: {{ $data['invoice']->seller_details->phone }}</p>
          <p>Website: {{ $data['invoice']->seller_details->alternate_website }}</p>
        </div>
        <div style="padding: 5px; display:inline-block; width: 49%; float:left; min-height: 100px; overflow:hidden;">
          @if($data['invoice']->buyer_details->company_name)
          <p>
            <b>Billed To: {{ $data['invoice']->buyer_details->name }} -- {{ $data['invoice']->buyer_details->company_name }}</b>
          </p>
          @else
          <p>
            <b>Billed To: {{ $data['invoice']->buyer_details->name }}</b>
          </p>
          @endif
          <p>Business Details</p>
          <p>Address: {{ $data['invoice']->buyer_details->address }}</p>
          <p>City|State: {{ $data['buyer']->city->city }}, {{ $data['buyer']->city->admin_name }}</p>
          <p>Zip Code: {{ $data['buyer']->business ? $data['buyer']->business->zip_code : '' }}</p>
          <p>Phone: {{ $data['invoice']->buyer_details->phone }}</p>
        </div>
        <div style="margin-top: 150px;">
          <table id="products" style="border: 1px solid #ccc; width:100%;">
              <thead>
                  <tr>
                      <th>{{__('Sr.No')}}</th>
                      <th>{{__('Item Details')}}</th>
                      <th>{{__('Product Code')}}</th>
                      <th>{{__('Qty')}}</th>
                      <th>{{__('Unit')}}</th>
                      <th>{{__('Rate')}}</th>
                      <th>{{__('GST')}}</th>
                      <th>{{__('Amount Excl Tax')}}</th>
                      <th>{{__('Amount Tax')}}</th>
                      <th>{{__('Amount Incl Tax')}}</th>
                  </tr>
              </thead>
              <tbody>
                  @php
                      $productsTotal = 0;
                      $grandTotal = 0;
                      $excTaxProductTotal = 0;
                      $taxAmountProductTotal = 0;
                      $taxInclProductTotal = 0;
                      $salesTax = 0;

                  @endphp
                  @foreach ($data['products'] as $key => $product)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            {{ $product->name }}
                          </td>
                          <td>
                            {{ $product->code }}
                          </td>
                          <td>
                            {{ $product->quantity }}
                          </td>
                          <td>
                            {{ $product->unit }}
                          </td>
                          <td>
                            {{ $product->rate }}
                          </td>
                          <td>
                            {{ $product->gst }}
                          </td>
                          @php
                              $excTaxProductTotal = $product->rate * $product->quantity;
                              $taxAmountProductTotal = $excTaxProductTotal * ( $product->gst  / 100 );
                              $taxInclProductTotal = $excTaxProductTotal + $taxAmountProductTotal;
                              $productsTotal += $taxInclProductTotal;
                              $salesTax += $taxAmountProductTotal;
                          @endphp
                          <td>{{ 'Rs ' . $excTaxProductTotal }}</td>
                          <td>{{ 'Rs ' . $taxAmountProductTotal }}</td>
                          <td>{{ 'Rs ' . $taxInclProductTotal }}</td>
                      </tr>
                  @endforeach
                  @php
                    $grandTotal = $productsTotal + $data['invoice']->freight_charges + $salesTax;
                  @endphp
                  <tr>
                      <td colspan="9" style="text-align: right;">{{ __('Amount') }}:</td>
                      <td colspan="1">{{'Rs ' . $productsTotal}}</td>
                  </tr>
                  <tr>
                    <td colspan="9" style="text-align: right;">{{ __('Freight Charges') }}:</td>
                    <td colspan="1">{{'Rs ' . $data['invoice']->freight_charges}}</td>
                  </tr>
                  <tr>
                    <td colspan="9" style="text-align: right;">{{ __('Sales Tax') }}:</td>
                    <td colspan="1">{{'Rs ' . $salesTax}}</td>
                  </tr>
                  <tr>
                    <td colspan="9" style="text-align: right;">{{ __('Total') }}:</td>
                    <td colspan="1">{{'Rs ' . $grandTotal}}</td>
                  </tr>
              </tbody>
          </table>
      </div>
      @if (!empty($data['invoice']->terms_and_conditions))
      <div style="margin-top: 10px;">
        <span style="display: block;"> {{ __('Terms and Conditions') }}: </span>
        <div class="items"> {{ $data['invoice']->terms_and_conditions }} </div>
      </div>
      @endif
      @if (!empty($data['attachments']))
        @foreach($data['attachments'] as $key => $attachment)
        <div style="width: 100%; margin-top:20px;">
          <table>
            <tbody>
              <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $attachment->type)) }}</td>
                @if( $attachment->attachmentType != 'pdf' )
                <td>
                  <img width="100px" height="100px" src="{{ $attachment->attachment }}">
                </td>
                @else
                  <a href="{{ $attachment->attachmentPath }}"><img width="100px" height="100px" src="{{ $attachment->attachment }}"></a>
                @endif

              </tr>
            <tbody>
          </table>
        </div>
        @endforeach
      @endif

    </body>
</html>
