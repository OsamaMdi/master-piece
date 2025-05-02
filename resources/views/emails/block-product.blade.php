@component('mail::message')
# Hello {{ $product->user->name }},

We would like to inform you that your product "**{{ $product->name }}**" has been **temporarily blocked** for the following reason:

@component('mail::panel')
**Reason:** {{ $reason }}
**Block Duration:** {{ $duration }}
**Blocked From:** {{ now()->format('F j, Y – H:i A') }}
**Blocked Until:** {{ $product->blocked_until ? \Carbon\Carbon::parse($product->blocked_until)->format('F j, Y – H:i A') : 'Permanent' }}
@endcomponent

Your product will not be available for reservations during the block period.

If you have any questions or concerns, please contact our support team.

Thanks,
**{{ config('app.name') }} Team**
@endcomponent
