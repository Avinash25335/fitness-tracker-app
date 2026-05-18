@extends('layouts.dashboard')

@section('page-title', 'Admin Panel')
@section('page-subtitle', 'Platform overview and management')

@section('content')
<div class="space-y-6 fade-up">
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <a href="{{ route('admin.users') }}" class="text-xs text-brand hover:underline">Manage →</a>
            </div>
            <p class="text-3xl font-extrabold text-white mb-1">{{ $usersCount }}</p>
            <p class="text-xs text-gray-500">Total Users</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <a href="{{ route('admin.workouts.index') }}" class="text-xs text-brand hover:underline">Manage →</a>
            </div>
            <p class="text-3xl font-extrabold text-white mb-1">{{ $workoutsCount }}</p>
            <p class="text-xs text-gray-500">Workout Plans</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-white mb-1">{{ $bookingsCount }}</p>
            <p class="text-xs text-gray-500">Total Bookings</p>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card p-6">
        <h3 class="font-bold text-white text-base mb-5">Recent Bookings</h3>
        @if($recentBookings->isNotEmpty())
        <div class="space-y-3">
            @foreach($recentBookings as $booking)
            <div class="flex items-center gap-4 p-3 rounded-xl bg-surface hover:bg-surface-3 transition">
                <div class="w-9 h-9 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 text-sm font-bold shrink-0">
                    {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white">{{ $booking->user->name ?? '—' }}</p>
                    <p class="text-xs text-gray-500">with {{ $booking->trainer->user->name ?? 'Trainer' }} · {{ $booking->date }} {{ $booking->time_slot }}</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' }}">
                    {{ ucfirst($booking->status ?? 'pending') }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-gray-600 py-8 text-sm">No bookings yet.</p>
        @endif
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.workouts.create') }}" class="card p-5 hover:border-brand/30 hover:-translate-y-1 transition-all duration-200 flex items-center gap-4 group">
            <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center group-hover:bg-green-500/20 transition"><svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
            <div><p class="font-semibold text-white text-sm">Add Workout</p><p class="text-xs text-gray-500">Create a new plan</p></div>
        </a>
        <a href="{{ route('admin.blog.create') }}" class="card p-5 hover:border-brand/30 hover:-translate-y-1 transition-all duration-200 flex items-center gap-4 group">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center group-hover:bg-blue-500/20 transition"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
            <div><p class="font-semibold text-white text-sm">Write Article</p><p class="text-xs text-gray-500">Publish a blog post</p></div>
        </a>
        <a href="{{ route('admin.users') }}" class="card p-5 hover:border-brand/30 hover:-translate-y-1 transition-all duration-200 flex items-center gap-4 group">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center group-hover:bg-purple-500/20 transition"><svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
            <div><p class="font-semibold text-white text-sm">Manage Users</p><p class="text-xs text-gray-500">View all members</p></div>
        </a>
    </div>
</div>
@endsection
