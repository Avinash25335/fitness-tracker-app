@extends('layouts.dashboard')

@section('page-title', 'Manage Workouts')
@section('page-subtitle', 'Create and edit training programs')

@section('content')
<div class="space-y-6 fade-up">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-white">Workout Plans</h2>
            <p class="text-sm text-gray-400 mt-1">Total plans: {{ $workouts->count() }}</p>
        </div>
        <a href="{{ route('admin.workouts.create') }}" class="btn-primary flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Plan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($workouts as $workout)
            <div class="card p-6 flex flex-col group">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $workout->level === 'beginner' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : ($workout->level === 'intermediate' ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20') }} uppercase tracking-wider">
                        {{ $workout->level }}
                    </span>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.workouts.edit', $workout) }}" class="p-1.5 rounded-lg bg-surface-2 text-gray-400 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.workouts.destroy', $workout) }}" method="POST" class="inline" onsubmit="return confirm('Delete this plan?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg bg-surface-2 text-gray-400 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-white mb-2 group-hover:text-brand transition-colors">{{ $workout->title }}</h3>
                <p class="text-sm text-gray-500 mb-6 flex-grow leading-relaxed">{{ Str::limit($workout->description, 90) }}</p>
                <div class="pt-4 border-t border-border-col/50 flex items-center justify-between text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                    <span>{{ $workout->duration_weeks }} Weeks</span>
                    <span>{{ $workout->exercises_count ?? $workout->exercises->count() }} Exercises</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
