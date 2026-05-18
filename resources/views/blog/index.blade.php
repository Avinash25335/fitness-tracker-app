@extends('layouts.dashboard')

@section('page-title', 'Articles & Tips')
@section('page-subtitle', 'Stay informed with the latest fitness science')

@section('content')
<div class="space-y-10 fade-up">
    
    <!-- HEADER SECTION: Search & Filters -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        {{-- Search Bar Removed --}}
        <div class="flex-1 max-w-2xl">
            <h3 class="text-2xl font-black text-main-area tracking-tighter">Explore Knowledge</h3>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Curated Fitness Intelligence</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('blog.index', ['search' => request('search')]) }}" 
               class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all {{ !request('category') ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-adaptive text-gray-500 hover:text-main-area border border-adaptive' }}">
                All
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('blog.index', ['category' => $cat->slug, 'search' => request('search')]) }}" 
               class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all {{ request('category') === $cat->slug ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-adaptive text-gray-500 hover:text-main-area border border-adaptive' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>

    @if($featuredPost)
    <!-- FEATURED ARTICLE (Hero Section) -->
    <div class="relative group">
        <a href="{{ route('blog.show', $featuredPost) }}" class="flex flex-col lg:flex-row bg-gray-800 border border-gray-700 rounded-3xl overflow-hidden hover:border-brand/30 hover:shadow-2xl hover:shadow-brand/5 transition-all duration-500 bg-adaptive border-adaptive shadow-xl">
            <div class="lg:w-7/12 h-[300px] lg:h-[450px] overflow-hidden relative">
                <img src="{{ $featuredPost->image_url ?? 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=1200&auto=format&fit=crop' }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="{{ $featuredPost->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60"></div>
                <div class="absolute top-6 left-6">
                    <span class="px-4 py-1.5 rounded-full bg-brand text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl">Featured Article</span>
                </div>
            </div>
            <div class="lg:w-5/12 p-8 lg:p-12 flex flex-col justify-center">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-xs font-bold text-brand uppercase tracking-widest">{{ $featuredPost->category->name ?? 'Fitness' }}</span>
                    <span class="text-gray-600">•</span>
                    <span class="text-xs text-gray-500">⏱ {{ $featuredPost->read_time }} min read</span>
                </div>
                <h2 class="text-3xl lg:text-4xl font-black text-main-area mb-6 leading-tight group-hover:text-brand transition-colors">
                    {{ $featuredPost->title }}
                </h2>
                <p class="text-gray-500 leading-relaxed mb-8 line-clamp-3 font-medium">
                    {{ Str::limit(strip_tags($featuredPost->content), 200) }}
                </p>
                <div class="flex items-center justify-between mt-auto pt-6 border-t border-adaptive">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($featuredPost->author->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-main-area">{{ $featuredPost->author->name ?? 'Admin' }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase">{{ \Carbon\Carbon::parse($featuredPost->published_at)->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <span class="text-brand font-black text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                        Read Article <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                </div>
            </div>
        </a>
    </div>
    @endif

    <!-- ARTICLE GRID -->
    <div class="space-y-8">
        @if($posts->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <a href="{{ route('blog.show', $post) }}" class="flex flex-col bg-gray-800 border border-gray-700 rounded-3xl overflow-hidden hover:border-brand/30 hover:-translate-y-2 hover:shadow-2xl hover:shadow-brand/5 transition-all duration-500 group bg-adaptive border-adaptive shadow-lg">
                <div class="h-56 relative overflow-hidden shrink-0">
                    <img src="{{ $post->image_url ?? 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=600&auto=format&fit=crop' }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $post->title }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent"></div>
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 rounded-lg bg-gray-900/60 backdrop-blur-md border border-white/10 text-white text-[9px] font-black uppercase tracking-widest">
                            {{ $post->category->name ?? 'Fitness' }}
                        </span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">{{ \Carbon\Carbon::parse($post->published_at)->format('M d, Y') }}</p>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">⏱ {{ $post->read_time }} min read</p>
                    </div>
                    <h3 class="text-xl font-bold text-main-area mb-3 leading-tight group-hover:text-brand transition-colors line-clamp-2">
                        {{ $post->title }}
                    </h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6 line-clamp-2 flex-1 font-medium">
                        {{ Str::limit(strip_tags($post->content), 120) }}
                    </p>
                    <div class="flex items-center justify-between pt-5 border-t border-adaptive mt-auto">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-brand font-bold text-xs border border-adaptive bg-adaptive shadow-inner">
                                {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="text-xs text-gray-500 font-bold">{{ $post->author->name ?? 'Admin' }}</span>
                        </div>
                        <span class="text-brand text-xs font-black flex items-center gap-1 group-hover:gap-2 transition-all">
                            Read Article →
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- PAGINATION -->
        <div class="mt-12 py-10 border-t border-adaptive flex justify-center">
            {{ $posts->links() }}
        </div>
        @else
            <!-- EMPTY STATE -->
            @if(!$featuredPost)
            <div class="card p-20 text-center flex flex-col items-center bg-adaptive border-adaptive shadow-2xl">
                <div class="w-20 h-20 rounded-3xl bg-surface-2 border border-border-col flex items-center justify-center mb-6 bg-adaptive border-adaptive shadow-inner">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-main-area mb-2 tracking-tight">No articles found</h3>
                <p class="text-gray-500 mb-8 max-w-sm leading-relaxed font-medium">We couldn't find any articles matching your search or selected category.</p>
                <a href="{{ route('blog.index') }}" class="bg-brand text-white font-black px-8 py-3.5 rounded-xl hover:bg-brand-dark transition-all shadow-lg shadow-brand/20">
                    Clear All Filters
                </a>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection
