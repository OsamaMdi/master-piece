@component('mail::message')
# Hello {{ $product->user->name }},

We would like to inform you that your product "**{{ $product->name }}**" has been **deleted by the admin**.

@component('mail::panel')
**Product Name:** {{ $product->name }}
**Deleted On:** {{ now()->format('F j, Y – H:i A') }}
@if($reason)
**Reason:** {{ $reason }}
@endif
@endcomponent

If you have any questions or believe this was a mistake, please contact support.

Thanks,
**{{ config('app.name') }} Team**
@endcomponent
