@component('mail::message')
# Hello {{ $user->name }},

We regret to inform you that your account has been **temporarily blocked** due to the following reason:

@component('mail::panel')
**Reason:** {{ $reason }}
**Block Duration:** {{ $duration }}
**Blocked From:** {{ \Carbon\Carbon::now()->format('F j, Y – H:i A') }}
**Blocked Until:** {{ \Carbon\Carbon::now()->addDays(is_numeric($duration) ? $duration : 0)->format('F j, Y – H:i A') }}
@endcomponent

During this time, you will not be able to access your account or use our services.

If you believe this was a mistake or have any concerns, feel free to contact our support team.

Thank you for your understanding.
Sincerely,
**{{ config('app.name') }} Team**
@endcomponent
