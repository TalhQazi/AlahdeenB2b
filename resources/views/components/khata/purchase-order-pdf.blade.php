<html>
    <head>
          <style>
            p {
              line-height: 5px;
            }
            div.items {
                line-height: 20px;
                white-space: pre;
                min-height: 50px;
                overflow: hidden;
            }

        </style>
    </head>
    <body>
        @if( !empty($data['seller_details']->business) && !empty($data['seller_details']->business->additionalDetails) && !empty($data['seller_details']->business->additionalDetails->logo) )
        <div style="padding: 5px;">
          <img width="100px" height="100px" src="{{public_path($data['seller_details']->business->additionalDetails->logo)}}">
        </div>
        @endif
        <div style="padding: 5px; width: 100%;">
            <p>Created By: {{ $data['seller_details']->name }}</p>
            <p>PO Number: {{ $data['purchaseOrderInfo']->number }}</p>
            <p>PO Date: {{ $data['purchaseOrderInfo']->po_date}}</p>
            <p>Delivery Date: {{ $data['purchaseOrderInfo']->po_delivery_date }}</p>
        </div>
        <div>
          <span style="display: block;"> Item Description: </span>
          <div class="items"> {{ $data['purchaseOrderInfo']->order_description }} </div>
        </div>
        <div style="margin-top: 20px;">
          <span style="display: block;"> Payment Details: </span>
          <div class="items"> {{ $data['purchaseOrderInfo']->payment_details }} </div>
        </div>
        @if(!empty($data['purchaseOrderInfo']->attachment))
        <div style="width: 100%; margin-top:20px;">
          <table>
            <tbody>
              <tr>
                <td>Attachment:</td>
                @if($data['purchaseOrderInfo']->attachmentType != "pdf")
                <td>
                  <img width="100px" height="100px" src="{{ $data['purchaseOrderInfo']->attachment }}">
                </td>
                @else
                  <a href="{{ $data['purchaseOrderInfo']->attachment_path }}"><img width="100px" height="100px"><img width="100px" height="100px" src="{{ $data['purchaseOrderInfo']->attachment }}"></a>
                @endif

              </tr>
            <tbody>
          </table>
        </div>
        @endif
    </body>
</html>
