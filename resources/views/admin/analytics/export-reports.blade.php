@section('title', 'Librasense - Export Reports')
<x-app-layout>
    <title>Librasense - Export Reports</title>
    @section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Export Reports</h1>
            <p class="mt-2 text-gray-600">Download CSV reports for your library's data. Click a card below to export.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('admin.analytics.export-users') }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div>
                    <p class="text-sm font-medium text-gray-600">Users</p>
                    <p class="text-lg font-bold text-purple-600 mt-2">Export Users (CSV)</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg group-hover:bg-purple-100">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </a>
            <a href="{{ route('admin.analytics.export-visitors') }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div>
                    <p class="text-sm font-medium text-gray-600">Visitor Statistics</p>
                    <p class="text-lg font-bold text-primary-600 mt-2">Export Visitors (CSV)</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-lg group-hover:bg-primary-100">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </a>
            <a href="{{ route('admin.analytics.export-circulation') }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div>
                    <p class="text-sm font-medium text-gray-600">Book Circulation</p>
                    <p class="text-lg font-bold text-green-600 mt-2">Export Circulation (CSV)</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg group-hover:bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </a>
            <a href="{{ route('admin.analytics.export-inout') }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div>
                    <p class="text-sm font-medium text-gray-600">In/Out Tracking</p>
                    <p class="text-lg font-bold text-blue-600 mt-2">Export In/Out (CSV)</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </a>
        </div>
        <p class="mt-6 text-gray-500 text-sm text-center">Select a report to download as CSV. More export options coming soon.</p>
    </div>
</div>
@endsection
</x-app-layout> 