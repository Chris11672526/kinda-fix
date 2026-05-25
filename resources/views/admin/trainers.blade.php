<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-red-400">Trainers</p>
            <h1 class="mt-2 text-4xl font-black">Trainer Applications</h1>
            <p class="mt-2 text-neutral-400">Review and manage customer trainer hire requests.</p>

            {{-- ── Summary cards ──────────────────────────────────────── --}}
            @php
                $pending   = $applications->where('status', 'Pending')->count();
                $active    = $applications->where('status', 'Active')->count();
                $completed = $applications->where('status', 'Completed')->count();
                $cancelled = $applications->where('status', 'Cancelled')->count();
            @endphp
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-lg border border-yellow-500/20 bg-neutral-900 p-4">
                    <p class="text-xs uppercase tracking-widest text-neutral-400">Pending</p>
                    <p class="mt-1 text-3xl font-black text-yellow-300">{{ $pending }}</p>
                </div>
                <div class="rounded-lg border border-green-500/20 bg-neutral-900 p-4">
                    <p class="text-xs uppercase tracking-widest text-neutral-400">Active</p>
                    <p class="mt-1 text-3xl font-black text-green-300">{{ $active }}</p>
                </div>
                <div class="rounded-lg border border-blue-500/20 bg-neutral-900 p-4">
                    <p class="text-xs uppercase tracking-widest text-neutral-400">Completed</p>
                    <p class="mt-1 text-3xl font-black text-blue-300">{{ $completed }}</p>
                </div>
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-4">
                    <p class="text-xs uppercase tracking-widest text-neutral-400">Cancelled</p>
                    <p class="mt-1 text-3xl font-black text-neutral-400">{{ $cancelled }}</p>
                </div>
            </div>

            {{-- ── Filter tabs ─────────────────────────────────────────── --}}
            <div class="mt-6 flex gap-2 flex-wrap">
                @foreach (['All', 'Pending', 'Active', 'Completed', 'Cancelled'] as $tab)
                    <button onclick="filterTable('{{ $tab }}')"
                        data-tab="{{ $tab }}"
                        class="tab-btn rounded border border-white/10 bg-neutral-800 px-4 py-1.5 text-sm font-semibold text-neutral-300 hover:bg-neutral-700 transition">
                        {{ $tab }}
                    </button>
                @endforeach
            </div>

            {{-- ── Applications table ──────────────────────────────────── --}}
            <div class="mt-4 overflow-hidden rounded-lg border border-white/10">
                <table class="w-full text-sm" id="apps-table">
                    <thead class="bg-neutral-800 text-left text-xs uppercase tracking-widest text-neutral-400">
                        <tr>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Trainer</th>
                            <th class="px-4 py-3">Start Date</th>
                            <th class="px-4 py-3">Sessions</th>
                            <th class="px-4 py-3">Notes</th>
                            <th class="px-4 py-3">Applied</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 bg-neutral-900" id="apps-body">
                        @forelse ($applications as $app)
                            <tr data-status="{{ $app->status }}">
                                <td class="px-4 py-3">
                                    <p class="font-semibold">{{ $app->customer_first_name }} {{ $app->customer_last_name }}</p>
                                    <p class="text-xs text-neutral-400">{{ $app->customer_email }}</p>
                                    <p class="text-xs text-neutral-500">{{ $app->customer_phone }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold">{{ $app->trainer_first_name }} {{ $app->trainer_last_name }}</p>
                                    <p class="text-xs text-neutral-400">{{ $app->specialization }}</p>
                                </td>
                                <td class="px-4 py-3 text-neutral-300">
                                    {{ \Carbon\Carbon::parse($app->start_date)->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 text-neutral-300">
                                    {{ $app->sessions_done }} / {{ $app->sessions_total }}
                                </td>
                                <td class="px-4 py-3 text-neutral-400 max-w-[180px]">
                                    <span class="block truncate" title="{{ $app->notes }}">{{ $app->notes ?? '—' }}</span>
                                </td>
                                <td class="px-4 py-3 text-neutral-400 text-xs">
                                    {{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $color = match($app->status) {
                                            'Active'    => 'text-green-300 bg-green-500/10 border-green-500/30',
                                            'Pending'   => 'text-yellow-300 bg-yellow-500/10 border-yellow-500/30',
                                            'Cancelled' => 'text-neutral-400 bg-neutral-700/30 border-white/10',
                                            'Completed' => 'text-blue-300 bg-blue-500/10 border-blue-500/30',
                                            default     => 'text-neutral-400 bg-neutral-800 border-white/10',
                                        };
                                    @endphp
                                    <span class="rounded border px-2 py-0.5 text-xs font-bold {{ $color }}">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($app->status === 'Pending')
                                        <div class="flex gap-2">
                                            <button
                                                onclick="updateStatus({{ $app->id }}, 'Active', this)"
                                                class="rounded bg-green-600 px-3 py-1 text-xs font-bold text-white hover:bg-green-500 transition">
                                                Approve
                                            </button>
                                            <button
                                                onclick="updateStatus({{ $app->id }}, 'Cancelled', this)"
                                                class="rounded bg-red-700/60 px-3 py-1 text-xs font-bold text-red-300 hover:bg-red-700 transition">
                                                Reject
                                            </button>
                                        </div>
                                    @elseif ($app->status === 'Active')
                                        <button
                                            onclick="updateStatus({{ $app->id }}, 'Cancelled', this)"
                                            class="rounded border border-red-500/30 bg-red-500/10 px-3 py-1 text-xs font-bold text-red-400 hover:bg-red-500/20 transition">
                                            Cancel
                                        </button>
                                    @else
                                        <span class="text-xs text-neutral-600">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-neutral-500">No trainer applications yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        // ── Filter tabs ──────────────────────────────────────────────────────
        function filterTable(status) {
            document.querySelectorAll('[data-tab]').forEach(btn => {
                btn.classList.toggle('bg-red-600', btn.dataset.tab === status);
                btn.classList.toggle('border-red-600', btn.dataset.tab === status);
                btn.classList.toggle('text-white', btn.dataset.tab === status);
                btn.classList.toggle('bg-neutral-800', btn.dataset.tab !== status);
                btn.classList.toggle('text-neutral-300', btn.dataset.tab !== status);
            });
            document.querySelectorAll('#apps-body tr[data-status]').forEach(row => {
                row.style.display = (status === 'All' || row.dataset.status === status) ? '' : 'none';
            });
        }
        // Default to All
        filterTable('All');

        // ── Approve / Reject AJAX ─────────────────────────────────────────────
        function updateStatus(id, status, btn) {
            const label = status === 'Active' ? 'approve' : (status === 'Cancelled' ? 'reject/cancel' : status);
            if (!confirm(`Are you sure you want to ${label} this application?`)) return;

            btn.disabled = true;
            btn.textContent = '...';

            fetch(`/admin/trainers/${id}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Reload to reflect updated status
                    window.location.reload();
                } else {
                    alert(data.message || 'Update failed.');
                    btn.disabled = false;
                }
            })
            .catch(() => {
                alert('Network error. Please try again.');
                btn.disabled = false;
            });
        }
    </script>
</x-app-layout>