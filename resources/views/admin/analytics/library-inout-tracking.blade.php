@section('title', 'Librasense - Library In/Out Tracking')
<x-app-layout>
    <title>Librasense - Library In/Out Tracking</title>
    @section('content')
<div class="py-8">
    <h1 class="text-3xl font-bold mb-6">Library In/Out Tracking</h1>
    <!-- Comparison Filter Bar -->
    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-6 flex flex-col md:flex-row md:items-center gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date 1</label>
            <input type="date" name="date1" value="{{ $date1 }}" class="rounded-md border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date 2 (Compare)</label>
            <input type="date" name="date2" value="{{ $date2 }}" class="rounded-md border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
        </div>
        <div class="flex-1"></div>
        <button class="px-4 py-2 bg-primary-600 text-white rounded-md font-semibold hover:bg-primary-700">Compare</button>
    </form>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Peak Entry Times -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-semibold mb-4">Peak Entry Times</h2>
            <canvas id="peakEntryChart" height="200"></canvas>
        </div>
        <!-- Peak Exit Times -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-semibold mb-4">Peak Exit Times</h2>
            <canvas id="peakExitChart" height="200"></canvas>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Stay Duration Analysis -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-semibold mb-4">Stay Duration Analysis</h2>
            <div class="flex items-center gap-6 mb-4">
                <div>
                    <div class="text-xs text-gray-500">Average</div>
                    <div class="text-xl font-bold text-primary-600">{{ $data1['avg'] ? gmdate('G\h i\m', $data1['avg']*60) : 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Shortest</div>
                    <div class="text-xl font-bold text-green-600">{{ $data1['min'] ? gmdate('G\h i\m', $data1['min']*60) : 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Longest</div>
                    <div class="text-xl font-bold text-red-600">{{ $data1['max'] ? gmdate('G\h i\m', $data1['max']*60) : 'N/A' }}</div>
                </div>
            </div>
            <canvas id="stayDurationHistogram" height="200"></canvas>
        </div>
        <!-- Comparison Tools -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-semibold mb-4">Comparison</h2>
            <canvas id="comparisonChart" height="200"></canvas>
            <div class="text-xs text-gray-400 mt-2">Compare entry/exit or stay duration between two dates.</div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Use only 8am-6pm (8-18) for display
const hours = {!! json_encode($hours) !!};
const entriesByHour = {!! json_encode(array_slice($data1['entriesByHour'], 8, 11)) !!};
const exitsByHour = {!! json_encode(array_slice($data1['exitsByHour'], 8, 11)) !!};
const stayHistogram = {!! json_encode($data1['hist']) !!};
const comparisonEntries = @json($data2 ? array_slice($data2['entriesByHour'], 8, 11) : []);

// Peak Entry Times
new Chart(document.getElementById('peakEntryChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: hours,
        datasets: [{
            label: 'Entries',
            data: entriesByHour,
            backgroundColor: '#4F46E5',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
// Peak Exit Times
new Chart(document.getElementById('peakExitChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: hours,
        datasets: [{
            label: 'Exits',
            data: exitsByHour,
            backgroundColor: '#10B981',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
// Stay Duration Histogram
new Chart(document.getElementById('stayDurationHistogram').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['0-30m', '30-60m', '1-2h', '2-3h', '3-4h', '4h+'],
        datasets: [{
            label: 'Visitors',
            data: stayHistogram,
            backgroundColor: '#6366F1',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { 
            y: { 
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Visitors'
                },
                ticks: {
                    stepSize: 25,
                    callback: function(value) {
                        return value;
                    }
                },
                min: 0,
                max: 300
            },
            x: {
                title: {
                    display: true,
                    text: 'Stay Duration'
                }
            }
        }
    }
});
// Comparison Chart (side-by-side bar)
new Chart(document.getElementById('comparisonChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: hours,
        datasets: [
            {
                label: 'Entries ({{ $date1 }})',
                data: entriesByHour,
                backgroundColor: 'rgba(79, 70, 229, 0.7)'
            },
            @if($data2)
            {
                label: 'Entries ({{ $date2 }})',
                data: comparisonEntries,
                backgroundColor: 'rgba(16, 185, 129, 0.7)'
            }
            @endif
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
</x-app-layout> 