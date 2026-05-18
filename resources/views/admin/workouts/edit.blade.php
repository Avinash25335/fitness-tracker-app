@extends('layouts.dashboard')

@section('page-title', 'Edit Workout Plan')
@section('page-subtitle', 'Refine the training program details')

@section('content')
<div class="max-w-3xl mx-auto fade-up">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.workouts.index') }}" class="w-10 h-10 rounded-xl bg-surface-2 border border-border-col flex items-center justify-center text-gray-400 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="text-2xl font-extrabold text-white">Edit Plan: {{ $workout->title }}</h2>
    </div>

    <div class="card p-8">
        <form action="{{ route('admin.workouts.update', $workout) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Plan Title</label>
                <input type="text" name="title" value="{{ old('title', $workout->title) }}" required
                       class="input-field">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Description</label>
                <textarea name="description" rows="4" class="input-field">{{ old('description', $workout->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Difficulty Level</label>
                    <select name="level" class="input-field">
                        <option value="beginner" {{ $workout->level === 'beginner' ? 'selected' : '' }}>🟢 Beginner</option>
                        <option value="intermediate" {{ $workout->level === 'intermediate' ? 'selected' : '' }}>🟡 Intermediate</option>
                        <option value="advanced" {{ $workout->level === 'advanced' ? 'selected' : '' }}>🔴 Advanced</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Duration (Weeks)</label>
                    <input type="number" name="duration_weeks" value="{{ old('duration_weeks', $workout->duration_weeks) }}" required min="1" max="52"
                           class="input-field">
                </div>
            </div>

            <div class="pt-6 border-t border-border-col flex items-center justify-end gap-4">
                <a href="{{ route('admin.workouts.index') }}" class="btn-ghost">Cancel</a>
                <button type="submit" class="btn-primary px-8">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
