@component('mail::message')
<h1>We have received your request to reset your account password</h1>
<p>You can use the following code to recover your account:</p>

@component('mail::panel')
{{ $code }}
@endcomponent

<p>The above code is valid for 15 minutes from the time the message was sent.</p>
@endcomponent