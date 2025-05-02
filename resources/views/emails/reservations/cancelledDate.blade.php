@component('mail::message')
# Hello {{ $reservation->user->name }},

We regret to inform you that your reservation for **{{ $product->name }}** (from {{ $reservation->start_date }} to {{ $reservation->end_date }}) has been **cancelled** due to a technical issue with the tool.

We sincerely apologize for the inconvenience.

We will refund the full amount you paid within **2 business days**.

---

### You may be interested in similar tools:
@foreach ($suggestedProducts as $suggested)
- [{{ $suggested->name }}]({{ route('products.show', $suggested->id) }})
@endforeach

Thank you for your understanding.<br>
**The Rentify Team**
@endcomponent
