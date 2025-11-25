<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-400">
                Reports
            </p>
            <h2 class="font-semibold text-xl text-slate-50 leading-tight">
                Dashboard Grafik Booking
            </h2>
            <p class="text-xs text-slate-400 mt-1">
                Ringkasan performa booking berdasarkan periode yang dipilih.
            </p>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Filter Periode --}}
        <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Dari tanggal</label>
                    <input type="date" name="from" value="{{ $dateFrom }}"
                           class="rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20">
                </div>
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Sampai tanggal</label>
                    <input type="date" name="to" value="{{ $dateTo }}"
                           class="rounded-lg border-slate-700 bg-slate-950/70 text-sm text-slate-100 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-xs hover:bg-emerald-500/30 transition">
                        Terapkan
                    </button>
                    <a href="{{ route('admin.reports.dashboard') }}"
                       class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 text-xs hover:bg-slate-700 transition">
                        Reset
                    </a>
                </div>
            </form>

            <p class="text-[11px] text-slate-400">
                Periode: <span class="text-slate-100">{{ $dateFrom }}</span> s/d <span class="text-slate-100">{{ $dateTo }}</span>
            </p>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4">
                <p class="text-[11px] text-slate-400 mb-1">Total booking (all time)</p>
                <p class="text-2xl font-semibold text-slate-50">
                    {{ $totalAllTime }}
                </p>
            </div>

            <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4">
                <p class="text-[11px] text-slate-400 mb-1">Booking di periode ini</p>
                <p class="text-2xl font-semibold text-emerald-400">
                    {{ $totalInRange }}
                </p>
            </div>

            <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 flex flex-col justify-between">
                <div>
                    <p class="text-[11px] text-slate-400 mb-1">Completion rate (completed / total)</p>
                    <p class="text-2xl font-semibold text-sky-400">
                        {{ $completionRate }}%
                    </p>
                </div>
                <p class="text-[11px] text-slate-500 mt-2">
                    Layanan teratas:
                    @if($topService)
                        <span class="text-slate-100">{{ $topService->name }}</span>
                        <span class="text-slate-400">({{ $topService->total }} booking)</span>
                    @else
                        <span class="text-slate-500">-</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Booking Per Hari --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col">
                <h3 class="text-sm font-semibold text-slate-200 mb-3">
                    Booking per hari
                </h3>
                <div class="relative h-56">
                    <canvas id="dailyChart" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Distribusi Status --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col">
                <h3 class="text-sm font-semibold text-slate-200 mb-3">
                    Distribusi status
                </h3>
                <div class="relative h-56">
                    <canvas id="statusChart" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Layanan Terlaris --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col">
                <h3 class="text-sm font-semibold text-slate-200 mb-3">
                    Layanan terlaris
                </h3>
                <div class="relative h-56">
                    <canvas id="serviceChart" class="w-full h-full"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const bookingsPerDay = @json($bookingsPerDay);
        const statusStats    = @json($statusStats);
        const serviceStats   = @json($serviceStats);

        const dailyCtx   = document.getElementById('dailyChart').getContext('2d');
        const statusCtx  = document.getElementById('statusChart').getContext('2d');
        const serviceCtx = document.getElementById('serviceChart').getContext('2d');

        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: bookingsPerDay.map(i => i.date),
                datasets: [{
                    label: 'Total Booking',
                    data: bookingsPerDay.map(i => i.total),
                    borderWidth: 2,
                    borderColor: '#10b981',
                    tension: 0.4,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        ticks: { color: '#9ca3af', font: { size: 10 } },
                        grid: { color: '#1f2937' },
                    },
                    y: {
                        ticks: { color: '#9ca3af', font: { size: 10 } },
                        grid: { color: '#1f2937' },
                    }
                }
            }
        });

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusStats),
                datasets: [{
                    data: Object.values(statusStats),
                    backgroundColor: [
                        '#facc15',
                        '#22c55e',
                        '#60a5fa',
                        '#f87171'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#e5e7eb', font: { size: 10 } }
                    }
                }
            }
        });

        new Chart(serviceCtx, {
            type: 'bar',
            data: {
                labels: serviceStats.map(i => i.name),
                datasets: [{
                    label: 'Jumlah Booking',
                    data: serviceStats.map(i => i.total),
                    backgroundColor: '#0ea5e9'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        ticks: { color: '#9ca3af', font: { size: 10 } },
                        grid: { display: false },
                    },
                    y: {
                        ticks: { color: '#9ca3af', font: { size: 10 } },
                        grid: { color: '#1f2937' },
                    }
                }
            }
        });
    </script>
</x-app-layout>
