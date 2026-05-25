<x-guest-layout>
<div class="min-h-screen bg-neutral-950 text-white">

    {{-- Header --}}
    <header class="border-b border-white/8 bg-neutral-950/95 backdrop-blur sticky top-0 z-20">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-red-600 text-sm font-black shadow-lg shadow-red-900/40">FZ</span>
                <span>
                    <span class="block text-sm font-black leading-5">FitZone</span>
                    <span class="block text-[10px] font-bold uppercase tracking-widest text-red-400">Create Account</span>
                </span>
            </a>
            <a href="{{ route('customer.login') }}"
               class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-neutral-300 transition hover:border-white/20 hover:bg-white/10 hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Already a member? Sign in
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_.9fr]">

            {{-- ── Left: Form ──────────────────────────────────────────── --}}
            <div>
                {{-- Page title --}}
                <div class="mb-8">
                    <p class="text-xs font-black uppercase tracking-[.2em] text-red-400">New Membership</p>
                    <h1 class="mt-2 text-3xl font-black">Create your account</h1>
                    <p class="mt-2 text-neutral-400">Join FitZone today and start your fitness journey!</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-500/30 bg-red-950/40 px-5 py-4">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-2 text-sm font-semibold text-red-300 mb-2 last:mb-0">
                                <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $error }}
                            </p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.register') }}" class="space-y-5">
                    @csrf

                    {{-- Step 1: Plan & Branch --}}
                    <div class="rounded-2xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur">
                        <div class="mb-5 flex items-center gap-3">
                            <span class="grid h-7 w-7 place-items-center rounded-full bg-red-600 text-xs font-black text-white">1</span>
                            <h2 class="font-black text-lg">Choose Your Plan & Branch</h2>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="plan-select" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Membership Plan</label>
                                <select name="membership_plan_id" id="plan-select" required
                                        onchange="updatePlanPreview(this)"
                                        class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 text-sm text-white transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20 @error('membership_plan_id') border-red-500/70 @enderror">
                                    <option value="">— Select a plan —</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                                data-price="{{ $plan->price }}"
                                                data-days="{{ $plan->duration_days }}"
                                                data-desc="{{ $plan->description }}"
                                                {{ old('membership_plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} — ₱{{ number_format($plan->price, 0) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('membership_plan_id')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="branch-select" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Branch</label>
                                <select name="branch_id" id="branch-select" required
                                        class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 text-sm text-white transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20 @error('branch_id') border-red-500/70 @enderror">
                                    <option value="">— Select a branch —</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Plan preview strip --}}
                        <div id="plan-preview" class="hidden mt-4 rounded-xl border border-red-500/20 bg-red-950/10 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-widest text-red-400">Selected Plan</p>
                                    <p class="mt-1 text-lg font-black" id="preview-name">-</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-2xl font-black text-red-300" id="preview-price">-</p>
                                    <p class="text-xs text-neutral-500" id="preview-days">-</p>
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <div class="rounded-lg bg-neutral-950/70 px-3 py-2">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-neutral-600">Starts</p>
                                    <p class="mt-1 text-xs font-bold" id="preview-start">-</p>
                                </div>
                                <div class="rounded-lg bg-neutral-950/70 px-3 py-2">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-neutral-600">Expires</p>
                                    <p class="mt-1 text-xs font-bold text-red-300" id="preview-expiry">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Personal Info --}}
                    <div class="rounded-2xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur">
                        <div class="mb-5 flex items-center gap-3">
                            <span class="grid h-7 w-7 place-items-center rounded-full bg-red-600 text-xs font-black text-white">2</span>
                            <h2 class="font-black text-lg">Personal Information</h2>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="full-name" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Full Name</label>
                                <input type="text" id="full-name" name="full_name" value="{{ old('full_name') }}"
                                       placeholder="Juan Dela Cruz" required
                                       class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 text-sm text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20 @error('full_name') border-red-500/70 @enderror">
                                @error('full_name')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                       placeholder="09171234567" required
                                       class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 text-sm text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20 @error('phone') border-red-500/70 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       placeholder="you@example.com" required
                                       class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 text-sm text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20 @error('email') border-red-500/70 @enderror">
                                @error('email')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Account --}}
                    <div class="rounded-2xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur">
                        <div class="mb-5 flex items-center gap-3">
                            <span class="grid h-7 w-7 place-items-center rounded-full bg-red-600 text-xs font-black text-white">3</span>
                            <h2 class="font-black text-lg">Set Password</h2>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div x-data="{ show: false }">
                                <label for="password" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Password</label>
                                <div class="relative">
                                    <input x-bind:type="show ? 'text' : 'password'" id="password" name="password"
                                           placeholder="Min. 8 characters" required
                                           class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 pr-11 text-sm text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20 @error('password') border-red-500/70 @enderror">
                                    <button type="button" @click="show=!show" tabindex="-1"
                                            class="absolute inset-y-0 right-0 flex items-center px-4 text-neutral-500 hover:text-white transition">
                                        <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.563-4.308m3.14-2.42A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.308 2.566M3 3l18 18"/></svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div x-data="{ show: false }">
                                <label for="password-confirm" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Confirm Password</label>
                                <div class="relative">
                                    <input x-bind:type="show ? 'text' : 'password'" id="password-confirm" name="password_confirmation"
                                           placeholder="Repeat password" required
                                           class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 pr-11 text-sm text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                                    <button type="button" @click="show=!show" tabindex="-1"
                                            class="absolute inset-y-0 right-0 flex items-center px-4 text-neutral-500 hover:text-white transition">
                                        <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.563-4.308m3.14-2.42A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.308 2.566M3 3l18 18"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 4: Payment --}}
                    <div class="rounded-2xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur">
                        <div class="mb-5 flex items-center gap-3">
                            <span class="grid h-7 w-7 place-items-center rounded-full bg-red-600 text-xs font-black text-white">4</span>
                            <h2 class="font-black text-lg">Payment Method</h2>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="payment-method" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Payment Method</label>
                                <select id="payment-method" name="payment_method" required
                                        class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 text-sm text-white transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20 @error('payment_method') border-red-500/70 @enderror">
                                    <option value="">— Select method —</option>
                                    <option value="Cash" {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="GCash" {{ old('payment_method') === 'GCash' ? 'selected' : '' }}>GCash</option>
                                    <option value="Credit Card" {{ old('payment_method') === 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="Bank Transfer" {{ old('payment_method') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="PayMaya" {{ old('payment_method') === 'PayMaya' ? 'selected' : '' }}>PayMaya</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="reference-no" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Reference No. <span class="text-neutral-600">(optional)</span></label>
                                <input type="text" id="reference-no" name="reference_no" value="{{ old('reference_no') }}"
                                       placeholder="GCash/bank reference"
                                       class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 text-sm text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full rounded-xl bg-red-600 py-3 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-red-900/30 transition hover:bg-red-500 hover:shadow-red-900/50 active:scale-95">
                        Create Membership Account
                    </button>

                    <p class="text-center text-xs text-neutral-500">
                        Already have an account?
                        <a href="{{ route('customer.login') }}" class="font-bold text-red-400 transition hover:text-red-300">Sign in here →</a>
                    </p>
                </form>
            </div>

            {{-- ── Right: Info panel ────────────────────────────────────── --}}
            <div class="hidden lg:block lg:sticky lg:top-24 lg:h-fit space-y-4">

                {{-- Why join --}}
                <div class="rounded-2xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur">
                    <p class="text-xs font-black uppercase tracking-widest text-red-400">Why FitZone?</p>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-600/15 text-red-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-black">Best Equipment</p>
                                <p class="mt-0.5 text-xs text-neutral-500">State-of-the-art gym equipment</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-600/15 text-red-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-black">Expert Trainers</p>
                                <p class="mt-0.5 text-xs text-neutral-500">Certified coaches for guidance</p>
                            </div>
                        </div>
                      
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-600/15 text-red-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-black">4 Branches</p>
                                <p class="mt-0.5 text-xs text-neutral-500">Train at any of our locations</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Available plans list --}}
                <div class="rounded-2xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur">
                    <p class="text-xs font-black uppercase tracking-widest text-red-400 mb-4">All Plans</p>
                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @foreach ($plans as $plan)
                            <div class="flex items-center justify-between rounded-lg bg-neutral-950/70 px-3 py-2.5 cursor-pointer transition hover:bg-neutral-950/90 hover:border-red-500/30 border border-transparent"
                                 onclick="document.getElementById('plan-select').value='{{ $plan->id }}'; updatePlanPreview(document.getElementById('plan-select')); document.getElementById('plan-select').scrollIntoView({behavior:'smooth'});">
                                <div>
                                    <p class="text-xs font-bold">{{ $plan->name }}</p>
                                    <p class="text-[10px] text-neutral-500">{{ $plan->duration_days }} days</p>
                                </div>
                                <p class="text-xs font-black text-red-300">₱{{ number_format($plan->price, 0) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Already member CTA --}}
                <div class="rounded-2xl border border-white/8 bg-neutral-900/70 p-6 backdrop-blur text-center">
                    <p class="text-xs text-neutral-400">Already have an account?</p>
                    <a href="{{ route('customer.login') }}"
                       class="mt-3 flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-white/10 hover:border-white/20">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign In
                    </a>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
function updatePlanPreview(select) {
    const option  = select.options[select.selectedIndex];
    const preview = document.getElementById('plan-preview');

    if (!select.value) {
        preview.classList.add('hidden');
        return;
    }

    const price    = parseFloat(option.dataset.price);
    const days     = parseInt(option.dataset.days);
    const today    = new Date();
    const expiry   = new Date();
    expiry.setDate(today.getDate() + days);

    const fmt = d => d.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });

    document.getElementById('preview-name').textContent    = option.text.split(' — ')[0];
    document.getElementById('preview-price').textContent   = '₱' + price.toLocaleString('en-PH', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    document.getElementById('preview-days').textContent    = days + ' days';
    document.getElementById('preview-start').textContent   = fmt(today);
    document.getElementById('preview-expiry').textContent  = fmt(expiry);

    preview.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('plan-select');
    if (sel.value) updatePlanPreview(sel);
});
</script>
</x-guest-layout>