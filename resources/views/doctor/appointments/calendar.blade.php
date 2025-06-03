@extends('layouts.app')

@section('title', 'Appointment Calendar')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('doctor.appointments.partials.page-header')

        <!-- Calendar Component -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            @include('doctor.appointments.partials.calendar-header')
            @include('doctor.appointments.partials.calendar-grid')
        </div>
    </div>
</div>

@include('doctor.appointments.partials.appointment-modal')
@endsection

@push('scripts')
<!-- Set up routes for JavaScript -->
<script>
    window.appointmentRoutes = {
        index: "{{ route('doctor.appointments.index') }}",
        calendarData: "{{ route('doctor.appointments.calendar.data') }}",
        show: "{{ route('doctor.appointments.show', ':id') }}"
    };
</script>

<!-- Include separated JavaScript files -->
<script src="{{ asset('js/doctor/appointment-modal.js') }}"></script>
<script src="{{ asset('js/doctor/appointment-calendar.js') }}"></script>
<script src="{{ asset('js/doctor/calendar-init.js') }}"></script>
@endpush
