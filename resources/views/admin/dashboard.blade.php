<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Admin Overview</p>
            <h1 class="mt-2 text-4xl font-black">Gym Operations</h1>
            <p class="mt-2 text-neutral-400">Quick view of members, memberships, payments, and sessions.</p>

            {{-- ── Stat cards ──────────────────────────────────────────────── --}}
            <section class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('admin.members') }}"
                   class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-blue-500/40 transition">
                    <p class="text-xs font-bold uppercase tracking-widest text-neutral-400">Total Members</p>
                    <p class="mt-3 text-4xl font-black">{{ $totalCustomers }}</p>
                </a>
                <a href="{{ route('admin.members') }}"
                   class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-green-500/40 transition">
                    <p class="text-xs font-bold uppercase tracking-widest text-green-400">Active</p>
                    <p class="mt-3 text-4xl font-black text-green-300">{{ $activeMemberships }}</p>
                </a>
                <a href="{{ route('admin.members') }}"
                   class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-red-500/40 transition">
                    <p class="text-xs font-bold uppercase tracking-widest text-red-400">Expired</p>
                    <p class="mt-3 text-4xl font-black text-red-300">{{ $expiredMemberships }}</p>
                </a>
                <a href="{{ route('admin.payments') }}"
                   class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-blue-500/40 transition">
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-400">Revenue</p>
                    <p class="mt-3 text-3xl font-black">PHP {{ number_format($totalRevenue, 0) }}</p>
                </a>
            </section>

            {{-- ── 3-column charts ─────────────────────────────────────────── --}}
            <section class="mt-6 grid gap-4 lg:grid-cols-3">

                {{-- Bar: Monthly Registrations --}}
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-300">Monthly Registrations</p>
                    <h2 class="mt-1 text-lg font-black">New Members per Month</h2>
                    <div class="mt-4 h-48">
                        <canvas id="registrationsChart"></canvas>
                    </div>
                </div>

                {{-- Donut: Membership Status --}}
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-300">Membership Status</p>
                    <h2 class="mt-1 text-lg font-black">Breakdown</h2>
                    <div class="mt-4 h-48 flex items-center justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                {{-- Line: Revenue --}}
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                    <p class="text-xs font-bold uppercase tracking-widest text-teal-300">Revenue</p>
                    <h2 class="mt-1 text-lg font-black">Monthly (PHP)</h2>
                    <div class="mt-4 h-48">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </section>

            {{-- ── Latest Members + Recent Payments ───────────────────────── --}}
            <section class="mt-6 grid gap-6 lg:grid-cols-[1fr_.9fr]">
                <article class="rounded-lg border border-white/10 bg-neutral-900 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-black">Latest Members</h2>
                        <a href="{{ route('admin.members') }}" class="text-sm font-bold text-blue-300 hover:text-blue-200">View all</a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @foreach ($customers as $customer)
                            <div class="rounded bg-neutral-950 p-4">
                                <p class="font-bold">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                                <p class="text-sm text-neutral-400">{{ $customer->email }} · {{ $customer->phone }}</p>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="rounded-lg border border-white/10 bg-neutral-900 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-black">Recent Payments</h2>
                        <a href="{{ route('admin.payments') }}" class="text-sm font-bold text-blue-300 hover:text-blue-200">View all</a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @foreach ($payments as $payment)
                            <div class="rounded bg-neutral-950 p-4">
                                <p class="font-bold">PHP {{ number_format($payment->total_paid, 2) }}</p>
                                <p class="text-sm text-neutral-400">{{ $payment->payment_method }}</p>
                                <p class="mt-1 text-xs font-bold {{ $payment->status === 'Paid' ? 'text-green-300' : 'text-yellow-300' }}">
                                    {{ $payment->status }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </article>
            </section>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const tickColor = '#737373';
            const gridColor = 'rgba(255,255,255,0.05)';

            const axisX = { border:{display:false}, grid:{display:false}, ticks:{color:tickColor, font:{size:10}} };
            const axisY = { border:{display:false}, grid:{color:gridColor}, ticks:{color:tickColor, font:{size:10}}, beginAtZero:true };

            const tooltip = {
                backgroundColor:'#171717', titleColor:'#fff', bodyColor:'#a3a3a3',
                borderColor:'rgba(255,255,255,0.1)', borderWidth:1, padding:10,
            };

            // ── Bar: Registrations ─────────────────────────────────────────
            new Chart(document.getElementById('registrationsChart'), {
                type: 'bar',
                data: {
                    labels: @json($monthlyLabels),
                    datasets: [{
                        label: 'New Members',
                        data: @json($monthlyValues),
                        backgroundColor: '#2563eb',
                        hoverBackgroundColor: '#3b82f6',
                        borderRadius: 5,
                        borderSkipped: false,
                        barPercentage: 0.5,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend:{display:false}, tooltip },
                    scales: { x: axisX, y: { ...axisY, ticks:{...axisY.ticks, stepSize:1} } }
                }
            });

            // ── Donut: Membership Status ───────────────────────────────────
            @php
                $statusColors = ['Active'=>'#22c55e','Expired'=>'#ef4444','Cancelled'=>'#a3a3a3','Pending'=>'#eab308'];
                $breakdown = $membershipBreakdown ?? collect();
            @endphp
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($breakdown->pluck('status')),
                    datasets: [{
                        data: @json($breakdown->pluck('total')),
                        backgroundColor: @json($breakdown->map(fn($r) => $statusColors[$r->status] ?? '#525252')->values()),
                        borderColor: '#0a0a0a',
                        borderWidth: 3,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color:'#a3a3a3', font:{size:11}, padding:12, boxWidth:10, boxHeight:10 }
                        },
                        tooltip
                    }
                }
            });

            // ── Line: Revenue ──────────────────────────────────────────────
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: @json($monthlyLabels),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($monthlyRevenue ?? []),
                        borderColor: '#14b8a6',
                        backgroundColor: 'rgba(20,184,166,0.10)',
                        pointBackgroundColor: '#14b8a6',
                        pointBorderColor: '#0a0a0a',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend:{display:false}, tooltip },
                    scales: {
                        x: axisX,
                        y: { ...axisY, ticks:{ ...axisY.ticks,
                            callback: v => v >= 1000 ? 'P'+(v/1000).toFixed(0)+'k' : 'P'+v
                        }}
                    }
                }
            });
        </script>
    </div>
</x-app-layout>