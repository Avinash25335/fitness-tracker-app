@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-surface-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Trainer Dashboard</h1>
            <p class="text-gray-400">Welcome back, <span class="text-brand">{{ Auth::user()->name }}</span>! 👋</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students -->
            <div class="bg-surface-2 rounded-lg p-6 border border-border-col hover:border-brand transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm uppercase tracking-wider">Total Students</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalStudents ?? 0 }}</p>
                    </div>
                    <div class="bg-brand/10 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Experience -->
            <div class="bg-surface-2 rounded-lg p-6 border border-border-col hover:border-brand transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm uppercase tracking-wider">Experience</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $trainer->experience ?? 1 }}<span class="text-lg text-gray-400"> yrs</span></p>
                    </div>
                    <div class="bg-brand/10 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Sessions This Month -->
            <div class="bg-surface-2 rounded-lg p-6 border border-border-col hover:border-brand transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm uppercase tracking-wider">Sessions This Month</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $sessionsThisMonth }}</p>
                    </div>
                    <div class="bg-brand/10 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cost Per Session -->
            <div class="bg-surface-2 rounded-lg p-6 border border-border-col hover:border-brand transition relative group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm uppercase tracking-wider">Cost Per Session</p>
                        <div class="flex items-center gap-2 mt-2" id="costDisplay">
                            <p class="text-3xl font-bold text-white">${{ number_format($trainer->hourly_rate ?? 50, 2) }}</p>
                            <button type="button" onclick="document.getElementById('costDisplay').classList.add('hidden'); document.getElementById('costEdit').classList.remove('hidden');" class="text-gray-400 hover:text-brand transition opacity-0 group-hover:opacity-100 p-1 bg-surface-1 rounded" title="Change Cost">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('trainer.cost.update') }}" id="costEdit" class="hidden mt-2 flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <div class="relative">
                                <span class="absolute left-2 top-1.5 text-gray-400 font-bold">$</span>
                                <input type="number" step="0.01" min="10" max="500" name="hourly_rate" value="{{ old('hourly_rate', $trainer->hourly_rate ?? 50) }}" class="w-24 pl-6 pr-2 py-1.5 bg-surface-1 border border-border-col text-white rounded text-sm focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30">
                            </div>
                            <button type="submit" class="bg-brand hover:bg-brand-dark text-white px-3 py-1.5 rounded text-sm font-bold transition">Save</button>
                            <button type="button" onclick="document.getElementById('costEdit').classList.add('hidden'); document.getElementById('costDisplay').classList.remove('hidden');" class="text-gray-400 hover:text-white px-2 py-1.5 text-sm transition">Cancel</button>
                        </form>
                        @error('hourly_rate')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="bg-brand/10 p-3 rounded-lg shrink-0">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Specialization & Profile Info -->
        <div class="bg-surface-2 rounded-lg p-6 border border-border-col mb-8">
            <h2 class="text-xl font-bold text-white mb-4">Profile Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Specialization</p>
                    <p class="text-white font-semibold">{{ $trainer->specialization ?? 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Bio</p>
                    <p class="text-white">{{ $trainer->bio ?: 'No bio provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Upcoming Sessions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-surface-2 rounded-lg p-6 border border-border-col">
                    <h2 class="text-xl font-bold text-white mb-4">Upcoming Sessions</h2>
                    @if($upcomingSessions && $upcomingSessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($upcomingSessions as $session)
                                <div class="flex items-center justify-between p-4 bg-surface-1 rounded-lg border border-border-col/50 hover:border-brand transition">
                                    <div>
                                        <p class="text-white font-semibold">{{ $session->user->name ?? 'Unknown' }}</p>
                                        <p class="text-gray-400 text-sm">{{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }} at {{ $session->session_time ?? 'TBA' }}</p>
                                    </div>
                                    <span class="px-3 py-1 bg-brand/20 text-brand rounded-full text-xs font-semibold">
                                        {{ ucfirst($session->status ?? 'pending') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-center py-8">No upcoming sessions scheduled</p>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-surface-2 rounded-lg p-6 border border-border-col">
                <h2 class="text-xl font-bold text-white mb-4">Quick Stats</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Total Revenue (Completed)</p>
                        <p class="text-2xl font-bold text-brand">${{ number_format($revenue, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">This Month's Sessions</p>
                        <p class="text-2xl font-bold text-white">{{ $sessionsThisMonth }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Total Unique Students</p>
                        <p class="text-2xl font-bold text-white">{{ $totalStudents }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        @if($recentBookings->count() > 0)
        <div class="mt-8 bg-surface-2 rounded-lg p-6 border border-border-col">
            <h2 class="text-xl font-bold text-white mb-4">Recent Bookings</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border-col">
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Student</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Date</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Time</th>
                            <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                        <tr class="border-b border-border-col/50 hover:bg-surface-1 transition">
                            <td class="py-3 px-4 text-white">{{ $booking->user->name }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $booking->session_time ?? 'TBA' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 bg-brand/20 text-brand rounded text-xs font-semibold">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
