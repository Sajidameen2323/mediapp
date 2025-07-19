@extends('layouts.admin')

@section('title', 'Laboratory Management - Medi App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Laboratory Management</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Manage registered laboratories and their profiles</p>
        </div>
        <a href="{{ route('admin.laboratories.create') }}" 
           class="inline-flex items-center gap-2 bg-gray-900 dark:bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus"></i>
            Add New Laboratory
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Registered Laboratories</h3>
        </div>
        
        @if($laboratories->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laboratory</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Working Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Services</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($laboratories as $laboratory)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center">
                                                <i class="fas fa-flask text-white text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $laboratory->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $laboratory->user->email }}</div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500">License: {{ $laboratory->license_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $laboratory->address }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $laboratory->city }}, {{ $laboratory->state }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">{{ $laboratory->country }} - {{ $laboratory->postal_code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($laboratory->opening_time)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($laboratory->closing_time)->format('h:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        @if(is_array($laboratory->working_days))
                                            {{ implode(', ', array_map('ucfirst', $laboratory->working_days)) }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if(is_array($laboratory->services_offered) && count($laboratory->services_offered) > 0)
                                            {{ count($laboratory->services_offered) }} service{{ count($laboratory->services_offered) != 1 ? 's' : '' }}
                                        @else
                                            No services
                                        @endif
                                    </div>
                                    @if(is_array($laboratory->services_offered) && count($laboratory->services_offered) > 0)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ implode(', ', array_slice($laboratory->services_offered, 0, 2)) }}
                                            @if(count($laboratory->services_offered) > 2)
                                                +{{ count($laboratory->services_offered) - 2 }} more
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($laboratory->is_available)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Unavailable
                                        </span>
                                    @endif
                                  
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.laboratories.show', $laboratory) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                    <a href="{{ route('admin.laboratories.edit', $laboratory) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.laboratories.destroy', $laboratory) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this laboratory?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <i class="fas fa-trash mr-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $laboratories->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600 mb-4">
                    <i class="fas fa-flask text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No laboratories registered yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by adding your first laboratory to the platform.</p>
                <a href="{{ route('admin.laboratories.create') }}" 
                   class="inline-flex items-center gap-2 bg-gray-900 dark:bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus"></i>
                    Add First Laboratory
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
