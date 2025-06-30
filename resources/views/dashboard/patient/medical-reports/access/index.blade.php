@extends('layouts.app')

@section('title', 'Medical Report Access Management')

@section('content')
<x-patient-navigation />

<div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex-1">
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-sm">
                            <li>
                                <a href="{{ route('patient.medical-reports.index') }}" 
                                   class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                    <i class="fas fa-file-medical text-lg"></i>
                                </a>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 text-xs"></i>
                            </li>
                            <li>
                                <a href="{{ route('patient.medical-reports.index') }}" 
                                   class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 font-medium transition-colors">
                                    Medical Reports
                                </a>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 text-xs"></i>
                            </li>
                            <li>
                                <a href="{{ route('patient.medical-reports.show', $medicalReport) }}" 
                                   class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 font-medium transition-colors">
                                    Report Details
                                </a>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 text-xs"></i>
                            </li>
                            <li>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">Access Management</span>
                            </li>
                        </ol>
                    </nav>
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-shield text-white text-xl"></i>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                Access Management
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                Control which doctors can access your medical report
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('patient.medical-reports.show', $medicalReport) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Report Summary -->
        <div class="mb-8 bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-file-medical text-blue-600 dark:text-blue-400 mr-2"></i>
                    Report Summary
                </h2>
            </div>
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-md text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Author</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                @if($medicalReport->doctor && $medicalReport->doctor->user)
                                    {{ $medicalReport->doctor->user->name }}
                                @else
                                    No doctor assigned
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $medicalReport->consultation_date ? $medicalReport->consultation_date->format('M d, Y') : $medicalReport->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-diagnoses text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Diagnosis</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ Str::limit($medicalReport->assessment_diagnosis, 30) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Current Access List -->
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-users text-green-600 dark:text-green-400 mr-2"></i>
                            Doctors with Access ({{ $medicalReport->accessRecords->where('status', 'active')->count() }})
                        </h2>
                    </div>
                    <div class="px-6 py-6">
                        @if($medicalReport->accessRecords->where('status', 'active')->isEmpty())
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-user-slash text-gray-400 dark:text-gray-500 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Additional Access</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Only the report author currently has access to this medical report.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($medicalReport->accessRecords->where('status', 'active') as $access)
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-lg p-5">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-user-md text-green-600 dark:text-green-400"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        {{ $access->doctor->user->name }}
                                                    </h3>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $access->doctor->specialization ?? 'General Practitioner' }}
                                                    </p>
                                                    <div class="flex items-center space-x-4 mt-2">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            @if($access->access_type === 'author') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                            @endif">
                                                            <i class="fas fa-circle text-xs mr-1"></i>
                                                            {{ $access->access_type === 'author' ? 'Report Author' : 'Granted Access' }}
                                                        </span>
                                                        @if($access->expires_at)
                                                            <span class="inline-flex items-center text-xs text-gray-600 dark:text-gray-400">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                Expires {{ $access->expires_at->format('M d, Y') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($access->notes)
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                                            <i class="fas fa-sticky-note mr-1"></i>
                                                            {{ $access->notes }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($access->access_type !== 'author')
                                                <div class="flex items-center space-x-2">
                                                    <button type="button" 
                                                            onclick="openEditModal({{ $access->id }}, '{{ $access->expires_at ? $access->expires_at->format('Y-m-d') : '' }}', '{{ $access->notes }}')"
                                                            class="inline-flex items-center px-3 py-1.5 border border-blue-300 dark:border-blue-600 rounded-md text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Edit
                                                    </button>
                                                    <button type="button" 
                                                            onclick="openRevokeModal({{ $access->id }}, '{{ $access->doctor->user->name }}')"
                                                            class="inline-flex items-center px-3 py-1.5 border border-red-300 dark:border-red-600 rounded-md text-xs font-medium text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                                                        <i class="fas fa-ban mr-1"></i>
                                                        Revoke
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Grant Access Panel -->
            <div class="xl:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-user-plus text-blue-600 dark:text-blue-400 mr-2"></i>
                            Grant Access
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        @if($availableDoctors->isEmpty())
                            <div class="text-center py-4">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-check-circle text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">No additional doctors available to grant access to.</p>
                            </div>
                        @else
                            <form action="{{ route('patient.medical-reports.access.grant', $medicalReport) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Select Doctor
                                    </label>
                                    <select name="doctor_id" id="doctor_id" required
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Choose a doctor...</option>
                                        @foreach($availableDoctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Access Expires (Optional)
                                    </label>
                                    <input type="date" name="expires_at" id="expires_at" min="{{ now()->addDay()->format('Y-m-d') }}"
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('expires_at')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Notes (Optional)
                                    </label>
                                    <textarea name="notes" id="notes" rows="3" placeholder="Add a note about this access grant..."
                                              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Grant Access
                                </button>
                            </form>
                        @endif
                    </div>
                </div>                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-chart-bar text-blue-600 dark:text-blue-400 mr-2"></i>
                            Access Summary
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Doctors with Access</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                {{ $medicalReport->accessRecords->where('status', 'active')->count() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Report Author</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                @if($medicalReport->doctor && $medicalReport->doctor->user)
                                    {{ $medicalReport->doctor->user->name }}
                                @else
                                    No doctor assigned
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Granted Access</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                {{ $medicalReport->accessRecords->where('status', 'active')->where('access_type', 'granted')->count() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Revoked Access</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                {{ $medicalReport->accessRecords->where('status', 'revoked')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Access Modal -->
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-edit text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Edit Access Settings
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="edit_expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Access Expires
                                    </label>
                                    <input type="date" name="expires_at" id="edit_expires_at" min="{{ now()->addDay()->format('Y-m-d') }}"
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="edit_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Notes
                                    </label>
                                    <textarea name="notes" id="edit_notes" rows="3"
                                              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update Access
                    </button>
                    <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Revoke Access Modal -->
<div id="revokeModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRevokeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="revokeForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Revoke Access
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" id="revokeMessage">
                                    Are you sure you want to revoke access for this doctor?
                                </p>
                            </div>
                            <div class="mt-4">
                                <label for="revoke_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Reason for Revocation (Optional)
                                </label>
                                <textarea name="notes" id="revoke_notes" rows="3" placeholder="Enter reason for revoking access..."
                                          class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Revoke Access
                    </button>
                    <button type="button" onclick="closeRevokeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(accessId, expiresAt, notes) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    const expiresAtInput = document.getElementById('edit_expires_at');
    const notesInput = document.getElementById('edit_notes');
    
    form.action = `{{ route('patient.medical-reports.access.update', [$medicalReport, ':id']) }}`.replace(':id', accessId);
    expiresAtInput.value = expiresAt;
    notesInput.value = notes;
    
    modal.classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function openRevokeModal(accessId, doctorName) {
    const modal = document.getElementById('revokeModal');
    const form = document.getElementById('revokeForm');
    const message = document.getElementById('revokeMessage');
    
    form.action = `{{ route('patient.medical-reports.access.revoke', [$medicalReport, ':id']) }}`.replace(':id', accessId);
    message.textContent = `Are you sure you want to revoke access for Dr. ${doctorName}? This action cannot be undone.`;
    
    modal.classList.remove('hidden');
}

function closeRevokeModal() {
    document.getElementById('revokeModal').classList.add('hidden');
}

function openBulkModal() {
    // Implementation for bulk management modal
    alert('Bulk management feature coming soon!');
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const editModal = document.getElementById('editModal');
    const revokeModal = document.getElementById('revokeModal');
    
    if (event.target === editModal) {
        closeEditModal();
    }
    
    if (event.target === revokeModal) {
        closeRevokeModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeEditModal();
        closeRevokeModal();
    }
});
</script>
@endpush
@endsection
