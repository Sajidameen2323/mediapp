@extends('layouts.app')

@section('title', 'Blocked Time Slots - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Blocked Time Slots</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Block specific time slots for maintenance, personal time, or emergencies</p>
            </div>
            <a href="{{ route('admin.appointment-config.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Configuration
            </a>
        </div>
    </div>

    <!-- Add Blocked Slot Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700 mb-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                <i class="fas fa-plus text-red-600 mr-2"></i>
                Add Blocked Time Slot
            </h3>
            <form action="{{ route('admin.appointment-config.blocked-slots.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Doctor (Optional)</label>
                        <select name="doctor_id" id="doctor_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Doctors (Global Block)</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->user->name }} - {{ $doctor->specialization }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                        <input type="date" name="date" id="date" value="{{ old('date') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                        <select name="type" id="type" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @foreach(\App\Models\BlockedTimeSlot::getTypes() as $value => $label)
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason</label>
                    <input type="text" name="reason" id="reason" value="{{ old('reason') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                           placeholder="e.g., System Maintenance, Personal Leave" required>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="2" 
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                              placeholder="Additional notes or details about this blocked time">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recurring Settings -->
                <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="is_recurring" id="is_recurring" value="1" 
                               {{ old('is_recurring') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                        <label for="is_recurring" class="ml-2 block text-sm text-gray-700 dark:text-gray-300 font-medium">Make this a recurring block</label>
                    </div>
                    
                    <div id="recurring-options" class="grid grid-cols-1 md:grid-cols-2 gap-4" style="display: none;">
                        <div>
                            <label for="recurring_pattern" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recurring Pattern</label>
                            <select name="recurring_pattern" id="recurring_pattern" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Pattern</option>
                                @foreach(\App\Models\BlockedTimeSlot::getRecurringPatterns() as $value => $label)
                                    <option value="{{ $value }}" {{ old('recurring_pattern') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('recurring_pattern')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="recurring_end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                            <input type="date" name="recurring_end_date" id="recurring_end_date" value="{{ old('recurring_end_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('recurring_end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-900 dark:bg-gray-700 hover:from-red-700 hover:to-orange-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-ban mr-2"></i>
                        Block Time Slot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Blocked Slots List -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                <i class="fas fa-ban text-red-600 mr-2"></i>
                Current Blocked Time Slots
            </h3>
            
            @if($blockedSlots->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Doctor
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date & Time
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Reason
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Recurring
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($blockedSlots as $slot)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($slot->doctor)
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Dr. {{ $slot->doctor->user->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $slot->doctor->specialization }}</div>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 rounded-full">
                                                Global Block
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $slot->date->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($slot->type === 'personal') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                            @elseif($slot->type === 'maintenance') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                            @elseif($slot->type === 'holiday') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @elseif($slot->type === 'emergency') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                            {{ \App\Models\BlockedTimeSlot::getTypes()[$slot->type] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white max-w-xs">{{ $slot->reason }}</div>
                                        @if($slot->notes)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $slot->notes }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($slot->is_recurring)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 rounded-full">
                                                <i class="fas fa-redo-alt mr-1"></i>
                                                {{ \App\Models\BlockedTimeSlot::getRecurringPatterns()[$slot->recurring_pattern] ?? 'Yes' }}
                                            </span>
                                            @if($slot->recurring_end_date)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Until {{ $slot->recurring_end_date->format('M d, Y') }}</div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                                                <i class="fas fa-times mr-1"></i>
                                                One-time
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('admin.appointment-config.blocked-slots.destroy', $slot) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to remove this blocked time slot?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200">
                                                <i class="fas fa-trash mr-1"></i>
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($blockedSlots->hasPages())
                    <div class="mt-6">
                        {{ $blockedSlots->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i class="fas fa-ban text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No blocked time slots configured</h3>
                    <p class="text-gray-600 dark:text-gray-400">Add your first blocked time slot using the form above.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recurringCheckbox = document.getElementById('is_recurring');
    const recurringOptions = document.getElementById('recurring-options');
    
    recurringCheckbox.addEventListener('change', function() {
        if (this.checked) {
            recurringOptions.style.display = 'grid';
        } else {
            recurringOptions.style.display = 'none';
        }
    });
    
    // Show/hide on page load if checkbox is already checked
    if (recurringCheckbox.checked) {
        recurringOptions.style.display = 'grid';
    }
});
</script>
@endsection
