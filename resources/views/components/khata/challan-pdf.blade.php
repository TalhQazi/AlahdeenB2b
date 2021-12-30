<html>
    <head>
        <style>
            p {
                line-height: 5px;
            }
        </style>
    </head>
    <body>
        @if( !empty($data['seller_details']->business) && !empty($data['seller_details']->business->additionalDetails) && !empty($data['seller_details']->business->additionalDetails->logo) )
        <div style="padding: 5px;">
          <img width="100px" height="100px" src="{{public_path($data['seller_details']->business->additionalDetails->logo)}}">
        </div>
        @endif
        <div style="padding: 5px; display:inline-block; width: 49%; float:left;">
            <p>From: {{!empty($data['seller_details']->business) ? $data['seller_details']->name.' / '.$data['seller_details']->business->company_name : $data['seller_details']->name }}</p>
            <p>To: {{!empty($data['buyer_details']->business) ?  $data['buyer_details']->name.' / '.$data['buyer_details']->business->company_name : $data['buyer_details']->name }}</p>
            <p>Challan Date: {{$data['challanInfo']->challanInfo_date}}</p>
            <p>Items Included: {{$data['challanInfo']->items_included}}</p>
            <p>No of Pieces: {{$data['challanInfo']->no_of_pieces}}</p>
            <p>Approx Weight: {{$data['challanInfo']->weight}}</p>
        </div>
        <div style="width: 49%; float: right;">
          <p>Billed To: {{!empty($data['buyer_details']->business) ?  $data['buyer_details']->name.' / '.$data['buyer_details']->business->company_name : $data['buyer_details']->name }}</p>
          <p>Bilty No: {{$data['challanInfo']->bilty_no}}</p>
          <p>Courier Name: {{$data['challanInfo']->courier_name}}</p>
        </div>
        @if(!empty($data['challanInfo']->digital_signature))
        <div style="width: 100%; text-align: center; display:block; margin-top:200px;">
          <table>
            <tbody>
              <tr>
                <td>Signature:</td>
                <td>
                  <img width="100px" height="100px" src="{{public_path($data['challanInfo']->digital_signature)}}">
                </td>

              </tr>
            <tbody>
          </table>
        </div>
        @endif
    </body>
</html>
