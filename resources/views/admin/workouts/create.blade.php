@extends('layouts.dashboard')

@section('page-title', 'Create Workout Plan')
@section('page-subtitle', 'Design a new training program for members')

@section('content')
<div class="max-w-3xl mx-auto fade-up">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.workouts.index') }}" class="w-10 h-10 rounded-xl bg-surface-2 border border-border-col flex items-center justify-center text-gray-400 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="text-2xl font-extrabold text-white">New Workout Plan</h2>
    </div>

    <div class="card p-8">
        <form action="{{ route('admin.workouts.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Plan Title</label>
                <input type="text" name="title" required placeholder="e.g. 30-Day Shred"
                       class="input-field">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Description</label>
                <textarea name="description" rows="4" placeholder="Describe the goal and structure of this plan..."
                          class="input-field"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Difficulty Level</label>
                    <select name="level" class="input-field">
                        <option value="beginner">🟢 Beginner</option>
                        <option value="intermediate">🟡 Intermediate</option>
                        <option value="advanced">🔴 Advanced</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Duration (Weeks)</label>
                    <input type="number" name="duration_weeks" required min="1" max="52"
                           class="input-field">
                </div>
            </div>

            <div class="pt-6 border-t border-border-col flex items-center justify-end gap-4">
                <a href="{{ route('admin.workouts.index') }}" class="btn-ghost">Cancel</a>
                <button type="submit" class="btn-primary px-8">Create Workout Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection
