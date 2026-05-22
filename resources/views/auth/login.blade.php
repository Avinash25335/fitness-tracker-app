@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Panel - Branding -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-surface">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1920&auto=format&fit=crop"
                 class="w-full h-full object-cover opacity-30" alt="Gym">
            <div class="absolute inset-0 bg-gradient-to-br from-brand/20 via-surface/60 to-surface"></div>
        </div>
        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-2xl font-extrabold text-white">Fitness<span class="text-brand">Pro</span></span>
                </a>
            </div>
            <div>
                <blockquote class="text-2xl font-bold text-white leading-snug mb-4">
                    "The only bad workout<br>is the one that didn't happen."
                </blockquote>
                <div class="flex items-center gap-2 mt-6">
                    @foreach(['💪', '🏋️', '🔥', '⚡'] as $emoji)
                    <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-lg border border-white/10">{{ $emoji }}</div>
                    @endforeach
                </div>
                <div class="mt-8 grid grid-cols-3 gap-4">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
                        <p class="text-2xl font-extrabold text-brand">500+</p>
                        <p class="text-xs text-gray-400 mt-1">Members</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
                        <p class="text-2xl font-extrabold text-brand">50+</p>
                        <p class="text-xs text-gray-400 mt-1">Workouts</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
                        <p class="text-2xl font-extrabold text-brand">20+</p>
                        <p class="text-xs text-gray-400 mt-1">Trainers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md fade-up">
            <!-- Mobile Logo -->
            <div class="lg:hidden mb-8 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-xl font-extrabold text-white">Fitness<span class="text-brand">Pro</span></span>
            </div>

            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-white mb-2">Welcome back</h1>
                <p class="text-gray-500">Sign in to continue your fitness journey</p>
            </div>

            @if($errors->any())
            <div class="mb-6 flex items-start gap-3 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                {{-- Account Type --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Login As</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <input id="login_role_user" type="radio" name="role" value="user" class="sr-only peer" {{ old('role', 'user') === 'user' ? 'checked' : '' }}>
                            <label for="login_role_user" class="block cursor-pointer rounded-2xl border border-border-col bg-surface-2 p-4 transition-all duration-200 hover:border-brand peer-checked:border-brand peer-checked:bg-surface">
                                <div class="text-sm font-bold text-white">User</div>
                                <p class="text-xs text-gray-400 mt-1">Use workouts, nutrition tools, and book trainers.</p>
                            </label>
                        </div>
                        <div>
                            <input id="login_role_trainer" type="radio" name="role" value="trainer" class="sr-only peer" {{ old('role') === 'trainer' ? 'checked' : '' }}>
                            <label for="login_role_trainer" class="block cursor-pointer rounded-2xl border border-border-col bg-surface-2 p-4 transition-all duration-200 hover:border-brand peer-checked:border-brand peer-checked:bg-surface">
                                <div class="text-sm font-bold text-white">Trainer</div>
                                <p class="text-xs text-gray-400 mt-1">Access your trainer marketplace profile.</p>
                            </label>
                        </div>
                    </div>
                    @error('role')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="you@example.com"
                           class="w-full bg-surface-2 border border-border-col text-white rounded-xl px-4 py-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all duration-200 placeholder-gray-600 text-sm">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-brand hover:text-green-400 transition-colors">Forgot password?</a>
                    </div>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full bg-surface-2 border border-border-col text-white rounded-xl px-4 py-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all duration-200 placeholder-gray-600 text-sm">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-border-col bg-surface-2 text-brand focus:ring-brand">
                    <label for="remember" class="text-sm text-gray-400">Remember me for 30 days</label>
                </div>

                <!-- Cloudflare Turnstile -->
                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}"></div>

                <button type="submit"
                        class="w-full btn-primary text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-500/20 hover:shadow-green-500/30 hover:scale-[1.02] transition-all duration-200 text-sm">
                    Sign In to FitnessPro
                </button>
            </form>


            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-brand font-semibold hover:text-green-400 transition-colors">Create one free →</a>
            </p>
        </div>
    </div>
</div>
@endsection
