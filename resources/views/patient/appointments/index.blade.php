@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">My Appointments</h2>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Book New Appointment
                </a>
            </div>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('patient.appointments.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">Filter</button>
                                <a href="{{ route('patient.appointments.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Appointments List -->
            @if($appointments->count() > 0)
                <div class="row">
                    @foreach($appointments as $appointment)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</h6>
                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Doctor</small>
                                            <p class="mb-0 fw-bold">Dr. {{ $appointment->doctor->name }}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Time</small>
                                            <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Reason</small>
                                        <p class="mb-0">{{ Str::limit($appointment->reason, 80) }}</p>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Type</small>
                                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Priority</small>
                                            <span class="badge bg-{{ $appointment->priority == 'high' || $appointment->priority == 'urgent' ? 'danger' : ($appointment->priority == 'medium' ? 'warning' : 'success') }}">
                                                {{ ucfirst($appointment->priority) }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($appointment->symptoms)
                                        <div class="mb-3">
                                            <small class="text-muted">Symptoms</small>
                                            <p class="mb-0">{{ Str::limit($appointment->symptoms, 60) }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('patient.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                        
                                        @if($appointment->status == 'pending' && \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->gt(\Carbon\Carbon::now()->addHours(24)))
                                            <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $appointments->withQueryString()->links() }}
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Appointments Found</h4>
                        <p class="text-muted mb-4">You haven't booked any appointments yet or no appointments match your filter criteria.</p>
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Book Your First Appointment
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    font-size: 0.8rem;
}

.card-footer {
    border-top: 1px solid rgba(0,0,0,0.125);
}
</style>
@endpush
