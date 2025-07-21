@extends('layouts.patient')

@section('title', 'Medical Report Access Management')

@section('content')


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
                                                <div class="flex items-center space-x-2" x-data="accessActions">
                                                    <button type="button" 
                                                            @click="openEditModal({{ $access->id }}, '{{ $access->expires_at ? $access->expires_at->format('Y-m-d') : '' }}', '{{ addslashes($access->notes ?? '') }}')"
                                                            class="inline-flex items-center px-3 py-1.5 border border-blue-300 dark:border-blue-600 rounded-md text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Edit
                                                    </button>
                                                    <button type="button" 
                                                            @click="openRevokeModal({{ $access->id }}, '{{ addslashes($access->doctor->user->name) }}')"
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
<div x-data="editModal" x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" 
     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" 
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>
    
    <!-- Modal container -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-lg w-full mx-auto"
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform scale-95" 
             x-transition:enter-end="opacity-100 transform scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 transform scale-100" 
             x-transition:leave-end="opacity-0 transform scale-95">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-edit mr-3"></i>
                        Edit Access Settings
                    </h3>
                    <button @click="closeModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Form -->
            <form :action="formAction" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label for="edit_expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Access Expires
                        </label>
                        <input type="date" name="expires_at" id="edit_expires_at" 
                               x-model="expiresAt" x-ref="firstInput"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-all duration-200">
                    </div>
                    
                    <div>
                        <label for="edit_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-sticky-note mr-1"></i>
                            Notes
                        </label>
                        <textarea name="notes" id="edit_notes" rows="3" 
                                  x-model="notes"
                                  placeholder="Add notes about this access..."
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-all duration-200"></textarea>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6">
                    <button type="button" @click="closeModal()" 
                            class="flex-1 px-4 py-3 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white text-base font-medium rounded-xl hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 bg-blue-600 text-white text-base font-medium rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Update Access
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Revoke Access Modal -->
<div x-data="revokeModal" x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" 
     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" 
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>
    
    <!-- Modal container -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-lg w-full mx-auto"
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform scale-95" 
             x-transition:enter-end="opacity-100 transform scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 transform scale-100" 
             x-transition:leave-end="opacity-0 transform scale-95">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Revoke Access
                    </h3>
                    <button @click="closeModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <div class="flex items-start space-x-4 mb-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-times text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-700 dark:text-gray-300 text-lg font-medium mb-2">
                            Are you sure you want to revoke access?
                        </p>
                        <p class="text-gray-600 dark:text-gray-400" x-text="message"></p>
                        <div class="mt-3 px-4 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <p class="text-sm text-red-800 dark:text-red-200">
                                <i class="fas fa-info-circle mr-1"></i>
                                This action cannot be undone. The doctor will immediately lose access to this medical report.
                            </p>
                        </div>
                    </div>
                </div>

                <form :action="formAction" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-6">
                        <label for="revoke_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-comment mr-1"></i>
                            Reason for Revocation (Optional)
                        </label>
                        <textarea name="notes" id="revoke_notes" rows="3" 
                                  x-model="notes" x-ref="notesTextarea"
                                  placeholder="Enter reason for revoking access..."
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-all duration-200"></textarea>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" @click="closeModal()" 
                                class="flex-1 px-4 py-3 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white text-base font-medium rounded-xl hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 bg-red-600 text-white text-base font-medium rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                            <i class="fas fa-ban mr-2"></i>
                            Revoke Access
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Alpine.js Components
document.addEventListener('alpine:init', () => {
    
    // Shared actions for access management
    Alpine.data('accessActions', () => ({
        openEditModal(accessId, expiresAt, notes) {
            // Get the edit modal component
            const editModalComponent = Alpine.$data(document.querySelector('[x-data*="editModal"]'));
            if (editModalComponent) {
                editModalComponent.open(accessId, expiresAt, notes);
            }
        },
        
        openRevokeModal(accessId, doctorName) {
            // Get the revoke modal component
            const revokeModalComponent = Alpine.$data(document.querySelector('[x-data*="revokeModal"]'));
            if (revokeModalComponent) {
                revokeModalComponent.open(accessId, doctorName);
            }
        }
    }));

    // Edit Modal Component
    Alpine.data('editModal', () => ({
        isOpen: false,
        formAction: '',
        expiresAt: '',
        notes: '',

        open(accessId, expiresAt, notes) {
            this.formAction = `{{ route('patient.medical-reports.access.update', [$medicalReport, ':id']) }}`.replace(':id', accessId);
            this.expiresAt = expiresAt || '';
            this.notes = notes || '';
            this.isOpen = true;
            
            // Focus on first input after modal opens
            this.$nextTick(() => {
                this.$refs.firstInput?.focus();
            });
        },

        closeModal() {
            this.isOpen = false;
            // Reset form data
            setTimeout(() => {
                this.formAction = '';
                this.expiresAt = '';
                this.notes = '';
            }, 300); // Wait for transition to complete
        },

        // Handle escape key
        init() {
            this.$watch('isOpen', (value) => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
    }));

    // Revoke Modal Component
    Alpine.data('revokeModal', () => ({
        isOpen: false,
        formAction: '',
        message: '',
        notes: '',

        open(accessId, doctorName) {
            this.formAction = `{{ route('patient.medical-reports.access.revoke', [$medicalReport, ':id']) }}`.replace(':id', accessId);
            this.message = `You are about to revoke access for Dr. ${doctorName}. They will no longer be able to view this medical report.`;
            this.notes = '';
            this.isOpen = true;
            
            // Focus on textarea after modal opens
            this.$nextTick(() => {
                this.$refs.notesTextarea?.focus();
            });
        },

        closeModal() {
            this.isOpen = false;
            // Reset form data
            setTimeout(() => {
                this.formAction = '';
                this.message = '';
                this.notes = '';
            }, 300); // Wait for transition to complete
        },

        // Handle escape key
        init() {
            this.$watch('isOpen', (value) => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
    }));
});

// Global escape key handler for closing modals
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Close any open modals
        const editModal = document.querySelector('[x-data*="editModal"]');
        const revokeModal = document.querySelector('[x-data*="revokeModal"]');
        
        if (editModal && Alpine.$data(editModal).isOpen) {
            Alpine.$data(editModal).closeModal();
        }
        
        if (revokeModal && Alpine.$data(revokeModal).isOpen) {
            Alpine.$data(revokeModal).closeModal();
        }
    }
});
</script>

<style>
/* Alpine.js cloak styles */
[x-cloak] {
    display: none !important;
}

/* Custom scrollbar for modal content */
.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Dark mode scrollbar */
.dark .modal-content::-webkit-scrollbar-track {
    background: #374151;
}

.dark .modal-content::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark .modal-content::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>
@endpush
@endsection
