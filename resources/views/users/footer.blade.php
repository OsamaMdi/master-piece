

<!-- Call To Action Area Start -->
<section class="roberto-cta-area">
    <div class="container">
        <div class="cta-content bg-img bg-overlay jarallax" style="background-image: url('{{ asset('img/tool1.png') }}');">
            <div class="row align-items-center">
                <div class="col-12 col-md-7">
                    <div class="cta-text mb-50">
                        <h2>Contact us now!</h2>
                        <h6>Call us at <strong>0788863725</strong> to book directly or get support</h6>
                    </div>
                </div>
                <div class="col-12 col-md-5 text-md-end text-center">
                    <a href="{{ route('contact') }}" class="btn btn-primary mb-50" style="padding: 12px 30px; font-weight: 500;">
                        Contact Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action Area End -->
<!-- Partner Area Start -->
<div class="partner-area">

</div>

<!-- jQuery (مطلوب قبل Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ Bootstrap 5 Bundle (Modal + Popper included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ Flatpickr + SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<!-- ❗ Keep jQuery only if used by old plugins like owl.carousel -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/userSearch.js') }}"></script>
<script src="{{ asset('js/roberto.bundle.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/default-assets/active.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Bootstrap Bundle JS (مع Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ سكربتاتك الخاصة -->
<script src="{{ asset('js/reservation.js') }}"></script>

</body>

</html>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        @endif

        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#f1c40f'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        @endif
    });
</script>
@endpush
