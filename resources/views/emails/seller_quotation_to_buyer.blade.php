@component('mail::message')
# Hi {{ $buyer->name }}

You have received quotation from {{ $seller->business->company_name, $seller->name }}, Quotation is attached with email or you can click on following button to view online.

@component('mail::button', ['url' => url(Storage::url($quote->quotation_path))])
View Quotation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
