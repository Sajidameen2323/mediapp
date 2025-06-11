{{-- 
    Appointment Rating Modal Component
    Reusable modal for rating appointments
    
    Props:
    - appointment: Appointment model instance
    - modalId: string - unique ID for the modal (default: 'ratingModal')
--}}

@props([
    'appointment',
    'modalId' => 'ratingModal'
])

<!-- Rating Modal -->
<div id="{{ $modalId }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-center mx-auto w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/20">
                <i class="fas fa-star text-yellow-600 dark:text-yellow-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white text-center mt-5">Rate Your Appointment</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                    How was your experience with Dr. {{ $appointment->doctor->user->name }}?
                </p>
            </div>

            <form action="{{ route('patient.appointments.rate', $appointment) }}" method="POST" id="ratingForm_{{ $modalId }}">
                @csrf
                <div class="px-7 py-3 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-center">
                            Overall Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="rating-stars flex justify-center space-x-1" data-modal="{{ $modalId }}">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star cursor-pointer text-2xl text-gray-300 hover:text-yellow-400 transition-colors duration-200"
                                      data-rating="{{ $i }}">
                                    <i class="far fa-star"></i>
                                </span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating_{{ $modalId }}" required>
                    </div>

                    <div>
                        <label for="review_{{ $modalId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Review (Optional)
                        </label>
                        <textarea name="review" id="review_{{ $modalId }}" rows="3" maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"
                                  placeholder="Share your experience with this appointment..."></textarea>
                    </div>
                </div>

                <div class="px-4 py-3 text-center space-x-3">
                    <button type="submit" id="submitRating_{{ $modalId }}"
                            class="px-6 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-star mr-2"></i>
                        Submit Rating
                    </button>
                    <button type="button" onclick="hideRatingModal('{{ $modalId }}')"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white text-sm font-medium rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .rating-stars .star {
        cursor: pointer;
        transition: color 0.2s;
    }

    .rating-stars .star:hover,
    .rating-stars .star.active {
        color: #ffc107;
    }

    .rating-stars .star i {
        transition: all 0.2s;
    }

    .rating-stars .star:hover i,
    .rating-stars .star.active i {
        transform: scale(1.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Rating Modal Functions
    function showRatingModal(modalId = '{{ $modalId }}') {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function hideRatingModal(modalId = '{{ $modalId }}') {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
        // Reset form
        document.getElementById('rating_' + modalId).value = '';
        document.getElementById('review_' + modalId).value = '';
        document.getElementById('submitRating_' + modalId).disabled = true;
        // Reset stars
        document.querySelectorAll(`#${modalId} .rating-stars .star`).forEach(function(star) {
            const icon = star.querySelector('i');
            icon.classList.remove('fas');
            icon.classList.add('far');
            star.classList.remove('active');
        });
    }

    // Initialize rating functionality when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize rating functionality for this modal
        initializeRatingModal('{{ $modalId }}');
    });

    function initializeRatingModal(modalId) {
        const ratingContainer = document.querySelector(`#${modalId} .rating-stars`);
        if (!ratingContainer) return;

        const stars = ratingContainer.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating_' + modalId);
        const submitButton = document.getElementById('submitRating_' + modalId);

        // Rating functionality
        stars.forEach(function(star) {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                ratingInput.value = rating;

                // Enable submit button
                submitButton.disabled = false;

                // Update star display
                stars.forEach(function(s, index) {
                    const starRating = parseInt(s.dataset.rating);
                    const icon = s.querySelector('i');

                    if (starRating <= rating) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        s.classList.add('active');
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        s.classList.remove('active');
                    }
                });
            });

            // Star hover effects
            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                stars.forEach(function(s) {
                    const starRating = parseInt(s.dataset.rating);
                    const icon = s.querySelector('i');

                    if (starRating <= rating) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                });
            });
        });

        // Reset stars on mouse leave
        ratingContainer.addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingInput.value) || 0;
            stars.forEach(function(star) {
                const starRating = parseInt(star.dataset.rating);
                const icon = star.querySelector('i');

                if (currentRating && starRating <= currentRating) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
            });
        });
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('{{ $modalId }}');
        if (event.target === modal) {
            hideRatingModal('{{ $modalId }}');
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('{{ $modalId }}');
            if (modal && !modal.classList.contains('hidden')) {
                hideRatingModal('{{ $modalId }}');
            }
        }
    });
</script>
@endpush
