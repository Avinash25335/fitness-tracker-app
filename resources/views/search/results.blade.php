@extends('layouts.dashboard')

@section('page-title', 'Search Results')
@section('page-subtitle', 'Showing results for "' . $query . '"')

@section('content')
<div class="space-y-8 fade-up">
    @if($workouts->isEmpty() && $diets->isEmpty() && $posts->isEmpty())
        <div class="card p-16 text-center">
            <div class="w-20 h-20 rounded-full bg-gray-700/30 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">No results found</h2>
            <p class="text-gray-500 max-w-sm mx-auto">We couldn't find anything matching "{{ $query }}". Try searching for something else like "muscle" or "protein".</p>
            <a href="{{ route('dashboard') }}" class="btn-primary mt-8 inline-block">Back to Dashboard</a>
        </div>
    @else

        <!-- Workout Plans Results -->
        @if($workouts->isNotEmpty())
        <div>
            <h3 class="text-lg font-bold text-main-area mb-4 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-brand rounded-full"></span>
                Workout Plans ({{ $workouts->count() }})
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($workouts as $workout)
                <a href="{{ route('workouts.show', $workout) }}" class="card p-5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-brand/10 text-brand border border-brand/20 uppercase tracking-widest">{{ $workout->level }}</span>
                        <svg class="w-4 h-4 text-gray-600 group-hover:text-brand transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                    <h4 class="font-bold text-main-area mb-1 group-hover:text-brand transition-colors">{{ $workout->title }}</h4>
                    <p class="text-xs text-gray-500 line-clamp-2">{{ $workout->description }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Diet Plans Results -->
        @if($diets->isNotEmpty())
        <div>
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-orange-500 rounded-full"></span>
                Nutrition Plans ({{ $diets->count() }})
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($diets as $diet)
                <a href="{{ route('diets.show', $diet) }}" class="card p-5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-orange-500/10 text-orange-400 border border-orange-500/20 uppercase tracking-widest">{{ $diet->daily_calories }} kcal</span>
                        <svg class="w-4 h-4 text-gray-600 group-hover:text-orange-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                    <h4 class="font-bold text-white mb-1 group-hover:text-orange-400 transition-colors">{{ $diet->title }}</h4>
                    <p class="text-xs text-gray-500 line-clamp-2">{{ $diet->description }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Blog Post Results -->
        @if($posts->isNotEmpty())
        <div>
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
                Articles & Tips ({{ $posts->count() }})
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                <a href="{{ route('blog.show', $post) }}" class="card p-5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $post->published_at->format('M d, Y') }}</span>
                        <svg class="w-4 h-4 text-gray-600 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                    <h4 class="font-bold text-white mb-1 group-hover:text-blue-400 transition-colors">{{ $post->title }}</h4>
                    <p class="text-xs text-gray-500 line-clamp-2">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Trainer Results -->
        @if($trainers->isNotEmpty())
        <div>
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-purple-500 rounded-full"></span>
                Pro Trainers ({{ $trainers->count() }})
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($trainers as $trainer)
                <a href="{{ route('trainers.index') }}" class="card p-5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-purple-500/10 text-purple-400 border border-purple-500/20 uppercase tracking-widest">{{ $trainer->specialization }}</span>
                        <svg class="w-4 h-4 text-gray-600 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                    <h4 class="font-bold text-white mb-1 group-hover:text-purple-400 transition-colors">{{ $trainer->name }}</h4>
                    <p class="text-xs text-gray-500 line-clamp-2">{{ $trainer->bio }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    @endif
</div>
@endsection
