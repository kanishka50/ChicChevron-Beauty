@extends('admin.layouts.app')

@section('title', 'Complaint Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Complaint Management</h1>
        
        <!-- Status Filter -->
        <div class="flex items-center space-x-3">
            <label class="text-sm font-medium text-gray-600">Filter by Status:</label>
            <select onchange="window.location.href='{{ route('admin.complaints.index') }}?status=' + this.value" 
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="">All Complaints</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
    </div>

    <!-- Complaints Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-40">
                            Complaint #
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-64">
                            Customer
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-48">
                            Type
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-96">
                            Subject
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-32 text-center">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-40">
                            Date
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-32 text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($complaints as $complaint)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="text-sm font-medium text-gray-900">{{ $complaint->complaint_number }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $complaint->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $complaint->user->email }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $complaint->complaint_type_label }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900 max-w-xs truncate">{{ $complaint->subject }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $complaint->status_color }}">
                                    {{ $complaint->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ $complaint->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.complaints.show', $complaint) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-gray-500 mb-2">No complaints found</p>
                                    @if(request('status'))
                                        <a href="{{ route('admin.complaints.index') }}" class="text-blue-500 hover:text-blue-600 font-medium">
                                            View all complaints
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($complaints->hasPages())
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>
</div>
@endsection