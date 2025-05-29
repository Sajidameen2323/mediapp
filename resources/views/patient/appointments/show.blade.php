@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Appointment Details</h4>
                        <a href="{{ route('patient.appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Appointments
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        <span class="badge fs-6 bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <!-- Appointment Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-calendar text-primary"></i> Date & Time
                                    </h6>
                                    <p class="card-text">
                                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F d, Y') }}<br>
                                        <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-user-md text-primary"></i> Doctor Information
                                    </h6>
                                    <p class="card-text">
                                        <strong>Name:</strong> Dr. {{ $appointment->doctor->name }}<br>
                                        <strong>Email:</strong> {{ $appointment->doctor->email }}
                                        @if($appointment->doctor->profile && $appointment->doctor->profile->specialization)
                                            <br><strong>Specialization:</strong> {{ $appointment->doctor->profile->specialization }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle text-primary"></i> Appointment Type
                                    </h6>
                                    <p class="card-text">{{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-exclamation-triangle text-primary"></i> Priority
                                    </h6>
                                    <p class="card-text">
                                        <span class="badge bg-{{ $appointment->priority == 'high' || $appointment->priority == 'urgent' ? 'danger' : ($appointment->priority == 'medium' ? 'warning' : 'success') }}">
                                            {{ ucfirst($appointment->priority) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reason for Visit -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-clipboard-list text-primary"></i> Reason for Visit
                            </h6>
                            <p class="card-text">{{ $appointment->reason }}</p>
                        </div>
                    </div>

                    <!-- Symptoms -->
                    @if($appointment->symptoms)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-thermometer-half text-primary"></i> Symptoms
                                </h6>
                                <p class="card-text">{{ $appointment->symptoms }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Additional Notes -->
                    @if($appointment->notes)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-sticky-note text-primary"></i> Additional Notes
                                </h6>
                                <p class="card-text">{{ $appointment->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Doctor's Notes -->
                    @if($appointment->doctor_notes)
                        <div class="card mb-4 border-info">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-user-md text-info"></i> Doctor's Notes
                                </h6>
                                <p class="card-text">{{ $appointment->doctor_notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Prescription -->
                    @if($appointment->prescription)
                        <div class="card mb-4 border-success">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-prescription-bottle-alt text-success"></i> Prescription
                                </h6>
                                <p class="card-text">{{ $appointment->prescription }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Follow-up Instructions -->
                    @if($appointment->follow_up_instructions)
                        <div class="card mb-4 border-warning">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-redo text-warning"></i> Follow-up Instructions
                                </h6>
                                <p class="card-text">{{ $appointment->follow_up_instructions }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Appointment Timeline -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-history text-primary"></i> Appointment Timeline
                            </h6>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Appointment Booked</h6>
                                        <p class="mb-0 text-muted">{{ $appointment->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>

                                @if($appointment->confirmed_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Appointment Confirmed</h6>
                                            <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($appointment->confirmed_at)->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($appointment->completed_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Appointment Completed</h6>
                                            <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($appointment->completed_at)->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($appointment->cancelled_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-danger"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Appointment Cancelled</h6>
                                            <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($appointment->cancelled_at)->format('M d, Y h:i A') }}</p>
                                            @if($appointment->cancellation_reason)
                                                <p class="mb-0 text-muted"><small>Reason: {{ $appointment->cancellation_reason }}</small></p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($appointment->status == 'pending' && \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->gt(\Carbon\Carbon::now()->addHours(24)))
                                <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i> Cancel Appointment
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div>
                            @if($appointment->status == 'completed')
                                <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print Details
                                </button>
                            @endif
                            
                            @if($appointment->status == 'completed' && !$appointment->rating)
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ratingModal">
                                    <i class="fas fa-star"></i> Rate Appointment
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rating Modal -->
@if($appointment->status == 'completed' && !$appointment->rating)
<div class="modal fade" id="ratingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rate Your Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('patient.appointments.rate', $appointment) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Overall Rating</label>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star" data-rating="{{ $i }}">
                                    <i class="far fa-star"></i>
                                </span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="review" class="form-label">Review (Optional)</label>
                        <textarea name="review" id="review" class="form-control" rows="3" maxlength="500" placeholder="Share your experience with this appointment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content h6 {
    font-size: 14px;
    margin-bottom: 5px;
}

.timeline-content p {
    font-size: 12px;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-1px);
}

.badge.fs-6 {
    font-size: 1rem !important;
    padding: 0.5rem 1rem;
}

.rating-stars {
    font-size: 2rem;
    color: #ffc107;
}

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

@media print {
    .btn, .modal, .card-header {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Rating functionality
    $('.rating-stars .star').click(function() {
        const rating = $(this).data('rating');
        $('#rating').val(rating);
        
        // Update star display
        $('.rating-stars .star').each(function(index) {
            const starRating = $(this).data('rating');
            const icon = $(this).find('i');
            
            if (starRating <= rating) {
                icon.removeClass('far').addClass('fas');
                $(this).addClass('active');
            } else {
                icon.removeClass('fas').addClass('far');
                $(this).removeClass('active');
            }
        });
    });

    // Star hover effects
    $('.rating-stars .star').hover(
        function() {
            const rating = $(this).data('rating');
            $('.rating-stars .star').each(function() {
                const starRating = $(this).data('rating');
                const icon = $(this).find('i');
                
                if (starRating <= rating) {
                    icon.removeClass('far').addClass('fas');
                } else {
                    icon.removeClass('fas').addClass('far');
                }
            });
        },
        function() {
            const currentRating = $('#rating').val();
            $('.rating-stars .star').each(function() {
                const starRating = $(this).data('rating');
                const icon = $(this).find('i');
                
                if (currentRating && starRating <= currentRating) {
                    icon.removeClass('far').addClass('fas');
                } else {
                    icon.removeClass('fas').addClass('far');
                }
            });
        }
    );
});
</script>
@endpush
