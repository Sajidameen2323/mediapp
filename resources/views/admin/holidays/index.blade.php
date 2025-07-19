@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Holiday Request Management</h1>
                <p class="text-gray-600 dark:text-gray-300">Review and approve doctor holiday requests</p>
            </div> <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50 p-6 transition-all duration-200 hover:shadow-md dark:hover:shadow-gray-700/70">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                            <i class="fas fa-clipboard-list w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Requests</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50 p-6 transition-all duration-200 hover:shadow-md dark:hover:shadow-gray-700/70">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
                            <i class="fas fa-clock w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50 p-6 transition-all duration-200 hover:shadow-md dark:hover:shadow-gray-700/70">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/50 rounded-lg">
                            <i class="fas fa-check w-6 h-6 text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['approved'] }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50 p-6 transition-all duration-200 hover:shadow-md dark:hover:shadow-gray-700/70">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 dark:bg-red-900/50 rounded-lg">
                            <i class="fas fa-times w-6 h-6 text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rejected</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['rejected'] }}</p>
                        </div>
                    </div>
                </div>
            </div> <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50 mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-filter mr-2 text-blue-600 dark:text-blue-400"></i>
                        Filter Requests
                    </h3>
                </div>
                <form method="GET" action="{{ route('admin.holidays.index') }}" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select name="status"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400 transition-colors">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doctor</label>
                            <select name="doctor_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400 transition-colors">
                                <option value="">All Doctors</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400 transition-colors">
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center justify-center font-medium">
                                <i class="fas fa-search mr-2"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div> <!-- Holiday Requests Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600 dark:text-blue-400"></i>
                        Holiday Requests
                    </h2>
                </div>

                @if ($holidays->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <input type="checkbox" id="select-all"
                                            class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:focus:ring-blue-400/50 dark:bg-gray-700">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Doctor</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Title</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Dates</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Duration</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Submitted</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($holidays as $holiday)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="selected_holidays[]" value="{{ $holiday->id }}"
                                                class="holiday-checkbox rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:focus:ring-blue-400/50 dark:bg-gray-700">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                            {{ substr($holiday->doctor->user->name, 0, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Dr.
                                                        {{ $holiday->doctor->user->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $holiday->doctor->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $holiday->title }}</div>
                                            @if ($holiday->description)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ Str::limit($holiday->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($holiday->start_date)->format('M d, Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                to {{ \Carbon\Carbon::parse($holiday->end_date)->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($holiday->start_date)->diffInDays(\Carbon\Carbon::parse($holiday->end_date)) + 1 }}
                                            days
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($holiday->status === 'pending')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pending
                                                </span>
                                            @elseif($holiday->status === 'approved')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approved
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Rejected
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $holiday->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('admin.holidays.show', $holiday) }}"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-150 flex items-center">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    View
                                                </a>

                                                @if ($holiday->status === 'pending')
                                                    <form action="{{ route('admin.holidays.approve', $holiday) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-150 flex items-center"
                                                            onclick="return confirm('Are you sure you want to approve this holiday request?')">
                                                            <i class="fas fa-check mr-1"></i>
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.holidays.reject', $holiday) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150 flex items-center"
                                                            onclick="return confirm('Are you sure you want to reject this holiday request?')">
                                                            <i class="fas fa-times mr-1"></i>
                                                            Reject
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <!-- Bulk Actions --> --}}
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Selected: <span id="selected-count"
                                        class="font-semibold text-blue-600 dark:text-blue-400">0</span>
                                </span>
                                <div class="flex space-x-2">
                                    <button type="button" id="bulk-approve"
                                        class="bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                                        disabled>
                                        <i class="fas fa-check mr-1"></i>
                                        Approve Selected
                                    </button>
                                    <button type="button" id="bulk-reject"
                                        class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                                        disabled>
                                        <i class="fas fa-times mr-1"></i>
                                        Reject Selected
                                    </button>
                                </div>
                            </div>

                            <div>
                                {{ $holidays->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div
                            class="mx-auto h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-times text-2xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No holiday requests</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No holiday requests match your current
                            filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAll = document.getElementById('select-all');
                const holidayCheckboxes = document.querySelectorAll('.holiday-checkbox');
                const selectedCount = document.getElementById('selected-count');
                const bulkApprove = document.getElementById('bulk-approve');
                const bulkReject = document.getElementById('bulk-reject');

                function updateSelectedCount() {
                    const checked = document.querySelectorAll('.holiday-checkbox:checked');
                    selectedCount.textContent = checked.length;

                    const hasSelection = checked.length > 0;
                    bulkApprove.disabled = !hasSelection;
                    bulkReject.disabled = !hasSelection;
                }

                selectAll.addEventListener('change', function() {
                    holidayCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectedCount();
                });

                holidayCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateSelectedCount);
                });

                bulkApprove.addEventListener('click', function() {
                    const selected = Array.from(document.querySelectorAll('.holiday-checkbox:checked')).map(
                        cb => cb.value);
                    if (selected.length > 0 && confirm(
                            `Are you sure you want to approve ${selected.length} holiday request(s)?`)) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('admin.holidays.bulk-approve') }}';

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        selected.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'holiday_ids[]';
                            input.value = id;
                            form.appendChild(input);
                        });

                        document.body.appendChild(form);
                        form.submit();
                    }
                });

                bulkReject.addEventListener('click', function() {
                    const selected = Array.from(document.querySelectorAll('.holiday-checkbox:checked')).map(
                        cb => cb.value);
                    if (selected.length > 0 && confirm(
                            `Are you sure you want to reject ${selected.length} holiday request(s)?`)) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('admin.holidays.bulk-reject') }}';

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        selected.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'holiday_ids[]';
                            input.value = id;
                            form.appendChild(input);
                        });

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
