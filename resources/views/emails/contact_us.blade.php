@component('mail::message')
Hello

You have received email from <a href="mailto:{{ $contact->email }}" target="_blank">{{ $contact->email }}</a>

<p>Name: {{ $contact->name }}</p>
<p>Email: {{ $contact->email }}</p>
<p>Phone: {{ $contact->phone }}</p>
<p>Message: {{ $contact->message }}</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
