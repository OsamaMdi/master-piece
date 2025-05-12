@extends('layouts.merchants.app')

@push('styles')
<link href="{{ asset('css/styleSubcription.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold">Choose Your Subscription Plan</h2>

    <div class="row g-4">
       @php
    $plans = [
        [
            'label' => '3 Months',
            'duration' => 3,
            'price' => 15,
            'features' => [
                'Feature your tools at the top of search results',
                'Enable chat with users'
            ]
        ],
        [
            'label' => '6 Months',
            'duration' => 6,
            'price' => 25,
            'features' => [
                'Feature your tools at the top of search results',
                'Enable chat with users',
                'Increase product listing limit to 15 tools'
            ]
        ],
        [
            'label' => '12 Months',
            'duration' => 12,
            'price' => 40,
            'features' => [
                'Feature your tools at the top of search results',
                'Enable chat with users',
                'Increase product listing limit to 15 tools'
            ]
        ],
    ];
@endphp

        @foreach ($plans as $plan)
        <div class="col-md-4">
            <div class="card shadow-sm h-100 subscription-card" data-price="{{ $plan['price'] }}" data-label="{{ $plan['label'] }}" data-duration="{{ $plan['duration'] }}">
                <div class="card-body d-flex flex-column">
                    <h4 class="text-primary">{{ $plan['label'] }}</h4>
                    <h5 class="text-muted mb-3">${{ $plan['price'] }}</h5>
                    <ul class="list-unstyled mb-4">
                        @foreach ($plan['features'] as $feature)
                            <li> {{ $feature }}</li>
                        @endforeach
                    </ul>
                    <button class="btn btn-primary mt-auto subscribe-btn">Subscribe Now</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
<div class="modal" id="cardModal" tabindex="-1" aria-labelledby="cardModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form id="cardForm" method="POST" action="{{ route('merchant.subscription.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="cardModalLabel">Enter Credit Card Details</h5>
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are subscribing to <strong id="planName"></strong> for <strong id="planPrice"></strong>.</p>

                <input type="hidden" name="price" id="inputPrice">
                <input type="hidden" name="duration" id="inputDuration">
                <input type="hidden" name="subscription_type" id="inputPlanType">

                <div class="form-group mb-3">
                    <label for="cardName" class="form-label">Name on Card</label>
                    <input type="text" name="card_name" id="cardName" class="form-control" placeholder="John Doe">
                </div>
                <div class="form-group mb-3">
                    <label for="cardNumber" class="form-label">Card Number</label>
                    <input type="text" name="card_number" id="cardNumber" class="form-control" placeholder="1234 5678 9012 3456">
                </div>
                <div class="form-group mb-3">
                    <label for="expiry" class="form-label">Expiration Date (MM/YY)</label>
                    <input type="text" name="expiry_date" id="expiry" class="form-control" placeholder="MM/YY">
                </div>
                <div class="form-group mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" name="cvv" id="cvv" class="form-control" placeholder="3 digits">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success w-100">💳 Pay & Subscribe</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modalEl = document.getElementById("cardModal");
    const closeBtn = document.querySelector(".btn-close");
    const backdrop = document.createElement("div");
    backdrop.className = "modal-backdrop fade show";

    function showModal() {
        modalEl.style.display = "block";
        modalEl.classList.add("show");
        document.body.appendChild(backdrop);
        document.body.classList.add("modal-open");
    }

    function hideModal() {
        modalEl.style.display = "none";
        modalEl.classList.remove("show");
        document.body.classList.remove("modal-open");
        backdrop.remove();
    }

    document.querySelectorAll('.subscribe-btn').forEach(button => {
        button.addEventListener('click', function () {
            const card = this.closest('.subscription-card');
            const price = card.dataset.price;
            const label = card.dataset.label;
            const duration = card.dataset.duration;

            document.getElementById('planName').innerText = label;
            document.getElementById('planPrice').innerText = `$${price}`;
            document.getElementById('inputPrice').value = price;
            document.getElementById('inputPlanType').value = label;
            document.getElementById('inputDuration').value = duration;

            showModal();
        });
    });

    closeBtn?.addEventListener('click', hideModal);

    const cardForm = document.getElementById("cardForm");
    cardForm?.addEventListener("submit", function (e) {
        e.preventDefault();

        ["cardName", "cardNumber", "expiry", "cvv"].forEach(id => clearFieldError(id));

        let valid = true;
        const name = cardForm.card_name.value.trim();
        const number = cardForm.card_number.value.trim();
        const expiry = cardForm.expiry_date.value.trim();
        const cvv = cardForm.cvv.value.trim();

        if (!name) showFieldError("cardName", "Name on card is required."), valid = false;
        if (!number) showFieldError("cardNumber", "Card number is required."), valid = false;
        if (!expiry) showFieldError("expiry", "Expiry date is required."), valid = false;
        if (!cvv) showFieldError("cvv", "CVV is required."), valid = false;

        if (number && !/^(4[0-9]{15}|5[1-5][0-9]{14})$/.test(number.replace(/\s+/g, ''))) {
            showFieldError("cardNumber", "Invalid card number format.");
            valid = false;
        }
        if (expiry && !/^\d{2}\/\d{2}$/.test(expiry)) {
            showFieldError("expiry", "Invalid expiry date format.");
            valid = false;
        }
        if (cvv && !/^\d{3,4}$/.test(cvv)) {
            showFieldError("cvv", "Invalid CVV.");
            valid = false;
        }

        if (valid) cardForm.submit();
    });

    function showFieldError(id, message) {
        const field = document.getElementById(id);
        const container = field.closest('.form-group') || field.parentElement;
        field.classList.add('is-invalid');
        if (!container.querySelector('.text-danger')) {
            const error = document.createElement('div');
            error.className = 'text-danger mt-1';
            error.textContent = message;
            container.appendChild(error);
        }
    }

    function clearFieldError(id) {
        const field = document.getElementById(id);
        const container = field.closest('.form-group') || field.parentElement;
        field.classList.remove('is-invalid');
        container.querySelectorAll('.text-danger').forEach(el => el.remove());
    }
</script>
@endpush
