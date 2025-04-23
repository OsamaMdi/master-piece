<!-- Website Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="feedbackForm" method="POST" action="{{ route('user.websiteReview.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Leave Your Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Rating -->
                <div class="form-group mb-3">
                    <label>Rating</label>
                    <div class="star-rating d-flex gap-1 mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star star text-muted" data-value="{{ $i }}" style="cursor: pointer;"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" required>
                    <small id="ratingError" class="text-danger d-none">Please select a rating.</small>
                </div>

                <!-- Feedback Text -->
                <div class="form-group mb-3">
                    <label>Your Review</label>
                    <textarea name="review_text" class="form-control" rows="4" required></textarea>
                    <small id="reviewError" class="text-danger d-none">Please write your feedback.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit Feedback</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert Success Only -->
@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thank you!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    </script>
@endif

<!-- Validation & Rating Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.star-rating .fa-star');
        const ratingInput = document.getElementById('ratingInput');
        const form = document.getElementById('feedbackForm');
        const reviewText = form.querySelector('textarea[name="review_text"]');

        const ratingError = document.getElementById('ratingError');
        const reviewError = document.getElementById('reviewError');

        // Initialize stars
        stars.forEach(star => star.classList.add('text-muted'));

        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => highlight(index));
            star.addEventListener('mouseout', () => reset());
            star.addEventListener('click', () => {
                setRating(index + 1);
                ratingError.classList.add('d-none'); // Hide error
            });
        });

        function highlight(index) {
            stars.forEach((s, i) => {
                s.classList.toggle('text-warning', i <= index);
                s.classList.toggle('text-muted', i > index);
            });
        }

        function reset() {
            const val = parseInt(ratingInput.value);
            stars.forEach((s, i) => {
                s.classList.toggle('text-warning', i < val);
                s.classList.toggle('text-muted', i >= val);
            });
        }

        function setRating(val) {
            ratingInput.value = val;
            reset();
        }

        // Form validation
        form.addEventListener('submit', function (e) {
            const rating = parseInt(ratingInput.value);
            const review = reviewText.value.trim();
            let valid = true;

            if (!rating || rating < 1) {
                ratingError.classList.remove('d-none');
                valid = false;
            } else {
                ratingError.classList.add('d-none');
            }

            if (!review) {
                reviewError.classList.remove('d-none');
                valid = false;
            } else {
                reviewError.classList.add('d-none');
            }

            if (!valid) {
                e.preventDefault(); // Stop submission if invalid
            }
        });
    });
</script>
