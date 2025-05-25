@section('title', 'Librasense - Export Reports')
<x-app-layout>
    <title>Librasense - Export Reports</title>
    @section('content')
<div class="py-8">
    <h1 class="text-3xl font-bold mb-6">Export Reports</h1>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 max-w-xl mx-auto">
        <h2 class="text-lg font-semibold mb-4">Download Library Reports</h2>
        <div class="space-y-4">
            <button class="w-full px-4 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 font-semibold shadow">Export Visitor Statistics (CSV)</button>
            <button class="w-full px-4 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 font-semibold shadow">Export Book Circulation (CSV)</button>
            <button class="w-full px-4 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 font-semibold shadow">Export In/Out Tracking (CSV)</button>
        </div>
        <p class="mt-6 text-gray-500 text-sm text-center">Select a report to download as CSV. More export options coming soon.</p>
    </div>
</div>
@endsection
</x-app-layout> 