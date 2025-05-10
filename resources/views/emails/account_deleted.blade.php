@component('mail::message')
# Account Deleted ❌

Dear {{ $name }},

We’re writing to inform you that your account has been deleted from **{{ config('app.name') }}**.

@component('mail::panel')
If you believe this was a mistake or have any questions, feel free to reach out to us.
@endcomponent

@component('mail::button', ['url' => $contactUrl])
Contact Support
@endcomponent

Thanks for being with us,
**{{ config('app.name') }} Team**
@endcomponent
