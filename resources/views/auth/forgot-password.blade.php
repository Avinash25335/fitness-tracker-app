@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 bg-surface">
    <div class="w-full max-w-md fade-up">
        <div class="mb-8 text-center">
            <a href="{{ route('home') }}" class="flex items-center justify-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-2xl font-extrabold text-white">Fitness<span class="text-brand">Pro</span></span>
            </a>
            <h1 class="text-3xl font-extrabold text-white mb-2">Forgot Password?</h1>
            <p class="text-gray-500 text-sm">No worries, we'll send you reset instructions.</p>
        </div>

        @if(session('status'))
        <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm">
            {{ session('status') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 flex items-start gap-3 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 text-center">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="you@example.com"
                       class="w-full bg-surface-2 border border-border-col text-white rounded-xl px-4 py-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all duration-200 placeholder-gray-600 text-sm text-center">
            </div>

            <button type="submit"
                    class="w-full btn-primary text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-500/20 hover:shadow-green-500/30 hover:scale-[1.02] transition-all duration-200 text-sm uppercase tracking-widest">
                Send Reset Link
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-brand transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Login
            </a>
        </div>
    </div>
</div>
@endsection
