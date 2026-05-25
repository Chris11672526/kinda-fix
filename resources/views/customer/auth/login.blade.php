<x-guest-layout>
<div class="min-h-screen bg-neutral-950 text-white flex flex-col">

    {{-- Header --}}
    <header class="border-b border-white/8 bg-neutral-950/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-red-600 text-sm font-black shadow-lg shadow-red-900/40">FZ</span>
                <span>
                    <span class="block text-sm font-black leading-5">FitZone</span>
                    <span class="block text-[10px] font-bold uppercase tracking-widest text-red-400">Member Login</span>
                </span>
            </a>
            <a href="{{ route('customer.register') }}"
               class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-neutral-300 transition hover:border-white/20 hover:bg-white/10 hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                New Member? Register
            </a>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 flex items-center justify-center px-6 py-10">
        <div class="w-full max-w-sm">

            {{-- Card --}}
            <div class="rounded-2xl border border-white/8 bg-neutral-900/70 backdrop-blur p-8">

                {{-- Header --}}
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4">
                        <div class="grid h-14 w-14 place-items-center rounded-xl bg-gradient-to-br from-red-600 to-red-700 shadow-lg shadow-red-900/40">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-black uppercase tracking-[.2em] text-red-400">Member Access</p>
                    <h1 class="mt-2 text-2xl font-black">Welcome Back</h1>
                    <p class="mt-2 text-sm text-neutral-400">Sign in to your FitZone account</p>
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-2 text-xs font-semibold text-red-300">
                                <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $error }}
                            </p>
                        @endforeach
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('customer.login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="you@example.com"
                               class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 text-sm text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20 @error('email') border-red-500/70 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-neutral-400">Password</label>
                            <span class="text-[10px] font-bold text-neutral-500">Contact staff for reset</span>
                        </div>
                        <div class="relative mt-2">
                            <input x-bind:type="show ? 'text' : 'password'" id="password" name="password" required
                                   placeholder="••••••••"
                                   class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-3 pr-11 text-sm text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20 @error('password') border-red-500/70 @enderror">
                            <button type="button" @click="show=!show" tabindex="-1"
                                    class="absolute inset-y-0 right-0 flex items-center px-4 text-neutral-500 hover:text-white transition">
                                <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.563-4.308m3.14-2.42A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.308 2.566M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-white/10 bg-neutral-950 text-red-600 transition focus:ring-2 focus:ring-red-500/20">
                        <label for="remember" class="text-sm text-neutral-400">Remember me for 30 days</label>
                    </div>

                    <button type="submit"
                            class="w-full rounded-xl bg-red-600 py-3 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-red-900/30 transition hover:bg-red-500 hover:shadow-red-900/50 active:scale-95 mt-6">
                        Sign In
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="bg-neutral-900 px-2 text-neutral-500">OR</span>
                    </div>
                </div>

                {{-- Sign Up --}}
                <a href="{{ route('customer.register') }}"
                   class="flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/10 hover:border-white/20 w-full">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Create New Account
                </a>

                {{-- Footer --}}
                <p class="mt-6 text-center text-xs text-neutral-500">
                    <a href="{{ route('home') }}" class="text-neutral-400 transition hover:text-white">← Back to home</a>
                </p>

            </div>

        </div>
    </main>

</div>
</x-guest-layout>
