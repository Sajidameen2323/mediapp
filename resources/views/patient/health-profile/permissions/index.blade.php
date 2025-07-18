@extends('layouts.patient')

@section('title', 'Health Profile Access Permissions - Medi App')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Health Profile Access Permissions</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Control which doctors can access your health profile
                    information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('patient.health-profile.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-600 dark:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 dark:hover:bg-gray-600 transition-all duration-200">
                    <i class="fas fa-arrow-left"></i>
                    Back to Health Profile
                </a>
            </div>
        </div>

        <!-- Information Banner -->
        <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-1 mr-3"></i>
                <div>
                    <h3 class="font-medium text-blue-900 dark:text-blue-100">About Health Profile Access</h3>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                        You control who can view your health profile. Only doctors you've had appointments with can be
                        granted access.
                        You can grant or revoke access at any time to protect your privacy.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Current Permissions -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-user-check mr-3 text-green-600 dark:text-green-400"></i>
                        Granted Access ({{ $permissions->where('is_granted', true)->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($permissions->where('is_granted', true) as $permission)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-4 last:mb-0">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $permission->doctor->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $permission->doctorProfile ? $permission->doctorProfile->specialization : 'General Practice' }}
                                    </p>
                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-clock mr-1"></i>
                                        Granted {{ $permission->granted_at->diffForHumans() }}
                                    </div>
                                    @if ($permission->notes)
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-sticky-note mr-1"></i>
                                            {{ $permission->notes }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex flex-col space-y-2">
                                    <button
                                        onclick="showRevokeModal({{ $permission->id }}, '{{ $permission->doctor->name }}')"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 px-3 py-1 rounded-md border border-red-300 dark:border-red-600 hover:border-red-400 dark:hover:border-red-500 transition text-sm">
                                        <i class="fas fa-ban mr-1"></i>Revoke
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 dark:text-gray-500 mb-4">
                                <i class="fas fa-user-slash text-4xl"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">No doctors have been granted access to your health
                                profile yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Grant New Access -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-user-plus mr-3 text-blue-600 dark:text-blue-400"></i>
                        Grant New Access
                    </h3>
                </div>
                <div class="p-6">
                    @if ($doctorsWithoutPermission->count() > 0)
                        <form action="{{ route('patient.health-profile.permissions.grant') }}" method="POST"
                            class="space-y-4">
                            @csrf

                            <div>
                                <label for="doctor_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Select Doctor
                                </label>
                                <select name="doctor_id" id="doctor_id" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Choose a doctor...</option>
                                    @foreach ($doctorsWithoutPermission as $doctor)
                                        <option value="{{ $doctor->user->id }}">
                                            {{ $doctor->user->name }} - {{ $doctor->specialization }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="notes"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Notes (Optional)
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="Optional note about granting access...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $errors->notes->message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors duration-200">
                                <i class="fas fa-check mr-2"></i>
                                Grant Access
                            </button>
                        </form>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 dark:text-gray-500 mb-4">
                                <i class="fas fa-user-md text-4xl"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                No new doctors to grant access to.
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                You can only grant access to doctors who have had appointments with you and don't already
                                have access.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Revoked Permissions History -->
        @if ($permissions->where('is_granted', false)->count() > 0)
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-700 dark:to-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-user-times mr-3 text-red-600 dark:text-red-400"></i>
                        Revoked Access History
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($permissions->where('is_granted', false) as $permission)
                            <div
                                class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">
                                            {{ $permission->doctor->name }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $permission->doctorProfile ? $permission->doctorProfile->specialization : 'General Practice' }}
                                        </p>
                                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-clock mr-1"></i>
                                            @if ($permission->granted_at)
                                                Granted {{ $permission->granted_at->diffForHumans() }} •
                                            @endif
                                            Revoked {{ $permission->revoked_at->diffForHumans() }}
                                        </div>
                                        @if ($permission->notes)
                                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-sticky-note mr-1"></i>
                                                {{ $permission->notes }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <form action="{{ route('patient.health-profile.permissions.grant') }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="doctor_id" value="{{ $permission->doctor_id }}">
                                            <button type="submit"
                                                class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 px-3 py-1 rounded-md border border-green-300 dark:border-green-600 hover:border-green-400 dark:hover:border-green-500 transition text-sm">
                                                <i class="fas fa-redo mr-1"></i>Re-grant
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Revoke Permission Modal -->
    <div id="revokeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white">Revoke Access</h3>
                        <button onclick="hideRevokeModal()" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="revokeForm" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <p class="text-gray-700 dark:text-gray-300">
                            Are you sure you want to revoke health profile access from <strong id="doctorName"></strong>?
                        </p>

                        <div>
                            <label for="revoke_notes"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Reason for Revocation (Optional)
                            </label>
                            <textarea name="notes" id="revoke_notes" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Optional reason for revoking access..."></textarea>
                        </div>
                    </div>

                    <div class="flex space-x-3 mt-6">
                        <button type="button" onclick="hideRevokeModal()"
                            class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white text-base font-medium rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                            <i class="fas fa-ban mr-2"></i>
                            Revoke Access
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function showRevokeModal(permissionId, doctorName) {
            document.getElementById('doctorName').textContent = doctorName;
            document.getElementById('revokeForm').action = `/patient/health-profile/permissions/${permissionId}/revoke`;
            document.getElementById('revokeModal').classList.remove('hidden');
        }

        function hideRevokeModal() {
            document.getElementById('revokeModal').classList.add('hidden');
            document.getElementById('revoke_notes').value = '';
        }
    </script>
@endsection
