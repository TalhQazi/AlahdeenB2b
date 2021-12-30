@component('mail::message')
# Hi {{ $seller->name }}

You have received a new quotation request from <b>{{ $buyer->name }}</b>.

@component('mail::button', ['url' => route('chat.messages')])
View Request
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
