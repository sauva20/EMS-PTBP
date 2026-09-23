<x-app-layout>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-6 max-w-[95%] mx-auto py-6">
        
        <!-- Page Title Card -->
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#009B77]/10 rounded-xl flex items-center justify-center border border-[#009B77]/20">
                    <svg class="w-6 h-6 text-[#009B77]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Dashboard Overview') }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">View and analyze CO₂ emissions trends for {{ $year }}</p>
                </div>
            </div>
            <div class="flex justify-end">
                <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
                    <div class="w-28">
                        @php $yearOpts = [2024 => '2024', 2025 => '2025', 2026 => '2026']; @endphp
                        <x-custom-select name="year" :options="$yearOpts" :selected="$year" buttonClass="bg-white border-gray-400 text-gray-700 py-1.5" />
                    </div>
                    <x-primary-button type="submit">Filter</x-primary-button>
                </form>
            </div>
        </div>

        <div class="w-full">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 transition-all hover:shadow-md">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Total CO2 Emission Trend (MT)</h3>
                    <canvas id="trendChart" height="100"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 transition-all hover:shadow-md">
                    <div class="p-6 text-gray-900 flex flex-col items-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 w-full">Emissions by Source</h3>
                        <div class="w-2/3">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 transition-all hover:shadow-md">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Emissions by Building</h3>
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Trend Chart
            const ctxTrend = document.getElementById('trendChart').getContext('2d');
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                        label: 'CO2e (MT)',
                        data: {!! json_encode($trendData) !!},
                        borderColor: '#009B77',
                        backgroundColor: 'rgba(0, 155, 119, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Pie Chart
            const ctxPie = document.getElementById('pieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($pieLabels) !!},
                    datasets: [{
                        data: {!! json_encode($pieData) !!},
                        backgroundColor: [
                            '#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899', '#64748b'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // Bar Chart
            const ctxBar = document.getElementById('barChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($barLabels) !!},
                    datasets: [{
                        label: 'CO2e (MT)',
                        data: {!! json_encode($barData) !!},
                        backgroundColor: '#009B77',
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
</x-app-layout>
