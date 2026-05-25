<x-app-layout>
<div class="min-h-screen bg-neutral-950 text-white">

    {{-- Header --}}
    <section class="relative overflow-hidden border-b border-white/5">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_60%_40%_at_50%_-20%,rgba(37,99,235,.1),transparent)]"></div>
        <div class="relative mx-auto max-w-7xl px-6 py-10">
            <p class="text-xs font-black uppercase tracking-[.2em] text-blue-400">Analytics</p>
            <h1 class="mt-2 text-4xl font-black">Membership Reports</h1>
            <p class="mt-2 text-neutral-400">Comprehensive overview of registrations, membership status, and revenue insights.</p>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-6 py-8 space-y-6">

        {{-- KPI Stat Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-white/8 bg-gradient-to-br from-blue-950/40 to-neutral-900/40 p-6 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-neutral-400">Total Members</p>
                        <p class="mt-3 text-4xl font-black text-blue-300">{{ $totalCustomers }}</p>
                    </div>
                    <div class="text-4xl opacity-20">👥</div>
                </div>
            </div>
            <div class="rounded-xl border border-green-500/20 bg-gradient-to-br from-green-950/40 to-neutral-900/40 p-6 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-green-600">Active Now</p>
                        <p class="mt-3 text-4xl font-black text-green-300">{{ $activeMemberships }}</p>
                    </div>
                    <div class="text-4xl opacity-30">✓</div>
                </div>
            </div>
            <div class="rounded-xl border border-red-500/20 bg-gradient-to-br from-red-950/40 to-neutral-900/40 p-6 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-red-600">Expired</p>
                        <p class="mt-3 text-4xl font-black text-red-300">{{ $expiredMemberships }}</p>
                    </div>
                    <div class="text-4xl opacity-20">⌛</div>
                </div>
            </div>
            <div class="rounded-xl border border-amber-500/20 bg-gradient-to-br from-amber-950/40 to-neutral-900/40 p-6 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-amber-600">Total Revenue</p>
                        <p class="mt-3 text-2xl font-black text-amber-300">PHP {{ number_format($totalRevenue, 0) }}</p>
                    </div>
                    <div class="text-4xl opacity-25">💰</div>
                </div>
            </div>
        </div>

        {{-- Charts Grid --}}
        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Bar Chart: Monthly Registrations --}}
            <article class="rounded-xl border border-white/8 bg-neutral-900/70 p-7 backdrop-blur overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-400">📊 Monthly Registrations</p>
                        <h2 class="mt-1 text-lg font-black text-white">New Members Trend</h2>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="barChart"></canvas>
                </div>
            </article>

            {{-- Doughnut Chart: Membership Status --}}
            <article class="rounded-xl border border-white/8 bg-neutral-900/70 p-7 backdrop-blur overflow-hidden">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-blue-400">📈 Membership Breakdown</p>
                    <h2 class="mt-1 text-lg font-black text-white">Status Distribution</h2>
                </div>
                <div class="mt-6 flex items-center justify-center" style="height:280px">
                    <canvas id="doughnutChart" style="max-height:260px;max-width:260px"></canvas>
                </div>
                {{-- Legend --}}
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @foreach ($membershipBreakdown as $row)
                        @php
                            $colors = [
                                'Active' => ['bg-green-500/20', 'text-green-300', 'dot' => 'bg-green-400'],
                                'Expired' => ['bg-red-500/20', 'text-red-300', 'dot' => 'bg-red-400'],
                                'Cancelled' => ['bg-yellow-500/20', 'text-yellow-300', 'dot' => 'bg-yellow-400'],
                                'Frozen' => ['bg-blue-500/20', 'text-blue-300', 'dot' => 'bg-blue-400'],
                            ];
                            $color = $colors[$row->status] ?? ['bg-neutral-800', 'text-neutral-300', 'dot' => 'bg-neutral-400'];
                        @endphp
                        <div class="rounded-lg border border-white/5 {{ $color[0] }} px-3 py-2 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full {{ $color['dot'] }}"></span>
                                <span class="font-bold {{ $color[1] }}">{{ $row->status }}</span>
                            </div>
                            <p class="mt-1 text-xs text-neutral-400 font-black">{{ $row->total }} members</p>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        {{-- Line Chart: Registration Growth --}}
        <article class="rounded-xl border border-white/8 bg-neutral-900/70 p-7 backdrop-blur overflow-hidden">
            <div class="mb-4">
                <p class="text-xs font-black uppercase tracking-widest text-blue-400">📉 Growth Trend</p>
                <h2 class="mt-1 text-lg font-black text-white">Cumulative Member Growth Over Time</h2>
            </div>
            <div class="h-64">
                <canvas id="lineChart"></canvas>
            </div>
        </article>

        {{-- Summary Statistics --}}
        <div class="grid gap-6 md:grid-cols-3">
            <article class="rounded-xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-black uppercase tracking-widest text-neutral-400">Active Sessions</h3>
                    <span class="text-2xl">🏋️</span>
                </div>
                <p class="text-3xl font-black text-white">{{ $sessionCount }}</p>
                <p class="mt-2 text-xs text-neutral-500">Total attendance records</p>
            </article>

            <article class="rounded-xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-black uppercase tracking-widest text-neutral-400">Pending Payments</h3>
                    <span class="text-2xl">⏳</span>
                </div>
                <p class="text-3xl font-black text-yellow-400">{{ $pendingPayments }}</p>
                <p class="mt-2 text-xs text-neutral-500">Awaiting confirmation</p>
            </article>

            <article class="rounded-xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-black uppercase tracking-widest text-neutral-400">Avg. Revenue/Month</h3>
                    <span class="text-2xl">💵</span>
                </div>
                @php
                    $months = count($monthlyLabels);
                    $avgRevenue = $months > 0 ? intval($totalRevenue / max($months, 1)) : 0;
                @endphp
                <p class="text-3xl font-black text-green-400">PHP {{ number_format($avgRevenue, 0) }}</p>
                <p class="mt-2 text-xs text-neutral-500">Based on {{ $months }} months</p>
            </article>
        </div>

        {{-- Membership Status Breakdown Table --}}
        <article class="rounded-xl border border-white/8 bg-neutral-900/70 p-7 backdrop-blur overflow-hidden">
            <div class="mb-6">
                <p class="text-xs font-black uppercase tracking-widest text-blue-400">📋 Detailed Breakdown</p>
                <h2 class="mt-1 text-lg font-black text-white">Membership Status Summary</h2>
            </div>

            <div class="space-y-3">
                @php
                    $totalMemberships = $membershipBreakdown->sum('total');
                @endphp
                @foreach ($membershipBreakdown as $row)
                    @php
                        $percentage = $totalMemberships > 0 ? round(($row->total / $totalMemberships) * 100) : 0;
                        $colors = [
                            'Active' => ['bg-green-500/30', 'fill' => 'bg-green-500'],
                            'Expired' => ['bg-red-500/30', 'fill' => 'bg-red-500'],
                            'Cancelled' => ['bg-yellow-500/30', 'fill' => 'bg-yellow-500'],
                            'Frozen' => ['bg-blue-500/30', 'fill' => 'bg-blue-500'],
                        ];
                        $color = $colors[$row->status] ?? ['bg-neutral-700', 'fill' => 'bg-neutral-600'];
                    @endphp
                    <div class="rounded-lg border border-white/5 {{ $color['bg-green-500/30'] ?? $color[0] }} p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="text-2xl">
                                    @switch($row->status)
                                        @case('Active') ✓ @break
                                        @case('Expired') ⌛ @break
                                        @case('Cancelled') ✕ @break
                                        @case('Frozen') ❄️ @break
                                        @default 📌
                                    @endswitch
                                </div>
                                <div>
                                    <p class="font-black text-white">{{ $row->status }}</p>
                                    <p class="text-xs text-neutral-400">{{ $row->total }} members</p>
                                </div>
                            </div>
                            <p class="text-xl font-black text-blue-300">{{ $percentage }}%</p>
                        </div>
                        <div class="h-2 rounded-full bg-neutral-800 overflow-hidden">
                            <div class="h-full {{ $color['fill'] }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($monthlyLabels);
    const values = @json($monthlyValues);
    const gridColor = 'rgba(255,255,255,.04)';
    const tickColor = '#71717a';

    // Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'New Members',
                data: values,
                backgroundColor: 'rgba(59,130,246,.6)',
                borderColor: 'rgba(96,165,250,.8)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { color: tickColor, font: { size: 11 } }, grid: { color: gridColor } },
                y: { beginAtZero: true, ticks: { color: tickColor, stepSize: 1 }, grid: { color: gridColor } }
            },
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#18181b', titleColor: '#fff', bodyColor: '#a1a1aa', cornerRadius: 8, padding: 12 }
            }
        }
    });

    // Doughnut Chart
    const breakdown = @json($membershipBreakdown);
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
            labels: breakdown.map(r => r.status),
            datasets: [{
                data: breakdown.map(r => r.total),
                backgroundColor: [
                    'rgba(74,222,128,.8)',
                    'rgba(248,113,113,.8)',
                    'rgba(251,191,36,.8)',
                    'rgba(96,165,250,.8)'
                ],
                borderColor: '#09090b',
                borderWidth: 3,
                hoverOffset: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#18181b', titleColor: '#fff', bodyColor: '#a1a1aa', cornerRadius: 8, padding: 12 }
            }
        }
    });

    // Line Chart
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Total Registrations',
                data: values,
                borderColor: 'rgba(96,165,250,.9)',
                backgroundColor: 'rgba(37,99,235,.12)',
                pointBackgroundColor: 'rgba(96,165,250,1)',
                pointBorderColor: '#09090b',
                pointBorderWidth: 2,
                pointRadius: 6,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { color: tickColor, font: { size: 11 } }, grid: { color: gridColor } },
                y: { beginAtZero: true, ticks: { color: tickColor, stepSize: 1 }, grid: { color: gridColor } }
            },
            plugins: {
                legend: { labels: { color: '#a1a1aa' } },
                tooltip: { backgroundColor: '#18181b', titleColor: '#fff', bodyColor: '#a1a1aa', cornerRadius: 8, padding: 12 }
            }
        }
    });
</script>
</x-app-layout>
