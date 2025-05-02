@component('mail::message')
# Hello {{ $reservation->user->name }},

Due to a maintenance period on **{{ $reservation->product->name }}** from **{{ request('from_date') }}** to **{{ request('to_date') }}**, your reservation starting on **{{ $reservation->start_date }}** is affected.

### We're offering you an alternative:
- **New start date:** {{ $nextAvailable['start_date'] }}
- **New end date:** {{ $nextAvailable['end_date'] }}

@component('mail::button', ['url' => $approveUrl])
✅ Accept New Dates
@endcomponent

@component('mail::button', ['url' => $rejectUrl])
❌ Cancel Reservation
@endcomponent

**Reason for unavailability:**
_{{ $reason }}_

Thanks,
**{{ config('app.name') }}**
@endcomponent
