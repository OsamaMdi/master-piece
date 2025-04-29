@extends('layouts.user.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📋 My Activity</h2>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="activityTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="reservations-tab" data-bs-toggle="tab" data-bs-target="#reservations" type="button" role="tab">My Reservations</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">My Reviews</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">My Reports</button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="favorites-tab" data-bs-toggle="tab" data-bs-target="#favorites" type="button" role="tab">
                My Favorites
            </button>
        </li>
    </ul>

    <div class="tab-content mt-4" id="activityTabsContent">
        <!-- === RESERVATIONS TAB === -->
        <div class="tab-pane fade show active" id="reservations" role="tabpanel">
            @forelse($reservations as $reservation)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $reservation->product->name ?? 'Tool' }}</h5>
                            <p class="mb-0">From: {{ $reservation->start_date }} | To: {{ $reservation->end_date }}</p>
                            <small>Status: <strong>{{ ucfirst($reservation->status) }}</strong></small><br>
                            <small>Paid: {{ $reservation->paid_amount }} JOD | Total: {{ $reservation->total_price }} JOD</small>
                        </div>
                        <div class="text-end">
                            @if ($reservation->status === 'not_started')
                                <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger cancel-btn" data-start-date="{{ $reservation->start_date }}" type="submit">
                                        Cancel
                                    </button>
                                </form>
                            @endif

                            @if ($reservation->report)
                            <a href="{{ route('reports.show', $reservation->report->id) }}" class="btn btn-sm btn-info">
                                View My Report
                            </a>
                        @elseif ($reservation->status !== 'cancelled')
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reportModal{{ $reservation->id }}">Report</button>
                        @endif
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="reportModal{{ $reservation->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('reservations.report', $reservation->id) }}">
                            @csrf

                            <!-- hidden fields -->
                            <input type="hidden" name="type" value="reservation">
                            <input type="hidden" name="target_id" value="{{ $reservation->id }}">

                            <div class="modal-content">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title">📝 Report Reservation - #{{ $reservation->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <p class="mb-2 text-muted">
                                        You are reporting a problem with your reservation of:
                                        <strong>{{ $reservation->product->name ?? 'Tool' }}</strong><br>
                                        <small>From {{ $reservation->start_date }} to {{ $reservation->end_date }}</small>
                                    </p>

                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Subject (Optional)</label>
                                        <input type="text" name="subject" class="form-control" placeholder="e.g. Tool was missing parts">
                                    </div>

                                    <div class="mb-3">
                                        <label for="message" class="form-label">Describe the issue</label>
                                        <textarea name="message" class="form-control" required placeholder="Please explain the problem..." rows="4"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-warning">
                                        Submit Report
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            @empty
                <p>No reservations found.</p>
            @endforelse

            <div class="mt-3">
                {{ $reservations->links() }}
            </div>
        </div>
                <!-- === REVIEWS TAB === -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <h5 class="mt-3">🛠️ Product Reviews</h5>
                    @forelse($productReviews as $review)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>{{ $review->product->name ?? 'Tool' }}</h6>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                <div class="text-warning mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <p>{{ $review->review_text }}</p>
                            </div>
                        </div>
                    @empty
                        <p>No product reviews found.</p>
                    @endforelse

                    {{ $productReviews->links() }}

                    <h5 class="mt-5">🌐 Website Reviews</h5>
                    @forelse($websiteReviews as $review)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Website Review</h6>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                <div class="text-warning mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <p>{{ $review->review_text }}</p>
                            </div>
                        </div>
                    @empty
                        <p>No website reviews found.</p>
                    @endforelse

                    {{ $websiteReviews->links() }}
                </div>

                <!-- === REPORTS TAB === -->
<div class="tab-pane fade" id="reports" role="tabpanel">
    <h5 class="mt-3">📢 Your Reports</h5>
    @forelse ($reports as $report)
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-1">{{ ucfirst($report->target_type) }} Report</h6>
                <small class="text-muted">Status: {{ ucfirst($report->status) }} | {{ $report->created_at->diffForHumans() }}</small>

                @if($report->subject)
                    <p class="mt-2 mb-1"><strong>Subject:</strong> {{ $report->subject }}</p>
                @endif

                <p><strong>Message:</strong> {{ $report->message }}</p>

                @if($report->status === 'pending' && strpos($report->message, '(Report withdrawn)') === false)
    <form method="POST" action="{{ route('reports.resolve', $report->id) }}">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-sm btn-outline-danger mt-2">
            Withdraw Report
        </button>
    </form>
@endif

            </div>
        </div>
    @empty
        <p>No reports submitted yet.</p>
    @endforelse

    {{ $reports->links() }}
</div>

            </div>


<!-- Favorites TAB -->
<div class="tab-pane fade" id="favorites" role="tabpanel">
    <h5 class="mt-3">❤️ Your Favorites</h5>

    @forelse ($favorites as $favorite)
        @if ($favorite->product)
            <div class="card mb-3 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ $favorite->product->images->isNotEmpty()
                            ? asset('storage/' . $favorite->product->images->sortByDesc('created_at')->first()->image_url)
                            : asset('img/logo.png') }}"
                            alt="{{ $favorite->product->name }}"
                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                            class="me-3">

                        <div>
                            <h5 class="mb-1">{{ $favorite->product->name }}</h5>
                            <small class="text-muted">{{ number_format($favorite->product->price, 2) }} JOD / Day</small>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('user.products.show', $favorite->product->id) }}" class="btn btn-sm btn-outline-primary">
                            View
                        </a>

                        @auth
                        <form action="{{ route('favorites.toggle', $favorite->product->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm text-danger" title="Remove from Favorites">
                                <i class="fa fa-heart"></i>
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        @endif
    @empty
        <p class="text-center">No favorites found.</p>
    @endforelse

    <div class="mt-3">
        {{ $favorites->links() }}
    </div>
</div>

</div>

        @endsection

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const cancelButtons = document.querySelectorAll('.cancel-btn');

                cancelButtons.forEach(function (btn) {
                    const form = btn.closest('form');
                    const startDateStr = btn.getAttribute('data-start-date');
                    const startDate = new Date(startDateStr);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        const diffInMs = startDate - today;
                        const diffInHours = diffInMs / (1000 * 60 * 60);

                        let title = '';
                        let html = '';
                        let icon = 'warning';

                        if (diffInHours <= 48) {
                            title = 'Late Cancellation';
                            html = `<b>⚠️ Note:</b><br>You are cancelling less than <strong>48 hours</strong> before the reservation starts.<br><br><strong>You will not receive a refund</strong> for the paid amount.<br><br>Are you sure you want to continue?`;
                        } else {
                            title = 'Cancel Reservation';
                            html = `Are you sure you want to cancel this reservation?<br><br><strong>No charges will be applied.</strong>`;
                        }

                        Swal.fire({
                            title: title,
                            html: html,
                            icon: icon,
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, cancel it!',
                            cancelButtonText: 'No, keep it'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
        @endpush

