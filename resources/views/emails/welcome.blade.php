@component('mail::message')
# Welcome {{ $user->name }}

Thanks for registering with us. {{ config('app.name') }} is the only complete platform to connect thousands of Manufacturers, Wholesellers, Importers with Millions of Retailers (Prospect Buyers) across Pakistan.

@component('mail::button', ['url' => config('app.front_end_url')])
Explore Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
