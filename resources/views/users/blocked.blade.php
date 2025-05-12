@extends('layouts.block')

@section('content')

<div class="blocked-container">
    <div class="blocked-card">
        <h2 class="blocked-title">🚫 Your Account is Blocked</h2>

        <p class="blocked-message">
            Sorry, your account has been blocked.
        </p>

        @if ($blocked_until)
            <p class="blocked-until">
                ⏳ Your block will expire on:
                <strong>{{ \Carbon\Carbon::parse($blocked_until)->format('d-m-Y H:i') }}</strong>
            </p>
        @endif

        @if ($block_reason)
            <div class="blocked-reason">
                <strong>Block Reason:</strong> {{ $block_reason }}
            </div>
        @endif

        <hr class="blocked-divider">

        @if ($reservations->count())
            <div class="blocked-reservations mb-4">
                <h4 class="mb-3">📅 Upcoming Reservations During Your Block Period</h4>
                <ul class="list-group">
                    @foreach($reservations as $res)
                        <li class="list-group-item">
                            @if(Auth::user()->user_type === 'user')
                                <strong>Product:</strong> {{ $res->product->name }}<br>
                                <strong>From:</strong> {{ $res->start_date }} - <strong>To:</strong> {{ $res->end_date }}
                            @else
                                <strong>User:</strong> {{ $res->user->name }}<br>
                                <strong>Product:</strong> {{ $res->product->name }}<br>
                                <strong>From:</strong> {{ $res->start_date }} - <strong>To:</strong> {{ $res->end_date }}
                            @endif

                            {{-- Cancel Form --}}
                            <form id="cancel-form-{{ $res->id }}"
                                  action="{{ Auth::user()->user_type === 'user'
                                        ? route('reservations.cancel', $res->id)
                                        : route('merchant.reservation.cancel', $res->id) }}"
                                  method="POST"
                                  style="display: none;">
                                @csrf
                                @method('PATCH')
                            </form>

                            {{-- Cancel Button --}}
                            <button class="btn btn-sm btn-danger mt-2"
                                    onclick="handleCancel('{{ $res->id }}', '{{ $res->start_date }}', '{{ Auth::user()->user_type }}')">
                                Cancel Reservation
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="alert alert-warning mt-3 small">
                    @if(Auth::user()->user_type === 'user')
                        We kindly ask that you complete all your reservations until the issue is resolved.
                    @else
                        Please continue fulfilling your product reservations until the issue is resolved.
                    @endif
                </div>
            </div>
        @endif

        <p class="blocked-contact-text">
            If you have any questions or concerns, feel free to contact us.
        </p>

        <a href="{{ route('contact') }}" class="blocked-contact-btn">
            Contact Us
        </a>

        <a href="javascript:history.back();" class="blocked-contact-btn mt-2">
            ← Back
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function handleCancel(resId, startDate, userType) {
        const today = new Date();
        const resDate = new Date(startDate);
        const diffTime = resDate - today;
        const diffDays = diffTime / (1000 * 60 * 60 * 24);

        if (userType === 'merchant' && diffDays < 2) {
            Swal.fire({
                icon: 'error',
                title: 'Too Late',
                text: 'You cannot cancel a reservation with less than 2 days remaining.',
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to cancel this reservation.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cancel-form-' + resId).submit();
            }
        });
    }
</script>
@endpush
