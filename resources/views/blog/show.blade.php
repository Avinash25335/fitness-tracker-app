@extends('layouts.dashboard')

@section('page-title', 'Read Article')
@section('page-subtitle', $post->category->name ?? 'Fitness Knowledge')

@section('content')
<div class="relative fade-up" x-data="{ 
    scrollProgress: 0,
    updateProgress() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        this.scrollProgress = (winScroll / height) * 100;
    }
}" @scroll.window="updateProgress()">
    
    <!-- Progress Bar (Sticky Top) -->
    <div class="fixed top-16 left-0 w-full h-1 z-50 bg-white/5 lg:left-64 lg:w-[calc(100%-16rem)]">
        <div class="h-full bg-brand transition-all duration-75" :style="`width: ${scrollProgress}%` text-brand"></div>
    </div>

    <!-- Back Button -->
    <div class="mb-10">
        <a href="{{ route('blog.index') }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-brand transition-colors text-sm font-bold uppercase tracking-widest">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Library
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- MAIN ARTICLE CONTENT -->
        <article class="lg:col-span-8 space-y-10">
            
            <!-- Header -->
            <header class="space-y-6">
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-lg bg-brand/10 text-brand text-[10px] font-black uppercase tracking-widest border border-brand/20">
                        {{ $post->category->name ?? 'Nutrition' }}
                    </span>
                    <span class="text-gray-600">•</span>
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">⏱ {{ $post->read_time }} min read</span>
                </div>
                
                <h1 class="text-4xl lg:text-5xl font-black text-main-area leading-[1.1] tracking-tight">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-6 pt-4 border-t border-adaptive">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-black text-lg">
                            {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-main-area">{{ $post->author->name ?? 'Admin' }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Scientific Contributor</p>
                        </div>
                    </div>
                    <div class="h-8 w-px bg-adaptive hidden md:block"></div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-0.5">Published On</p>
                        <p class="text-sm font-bold text-main-area">{{ \Carbon\Carbon::parse($post->published_at)->format('F d, Y') }}</p>
                    </div>
                </div>
            </header>

            <!-- Hero Image -->
            <div class="rounded-3xl overflow-hidden shadow-2xl border border-adaptive aspect-video">
                <img src="{{ $post->image_url ?? 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=1200&auto=format&fit=crop' }}" 
                     class="w-full h-full object-cover" alt="{{ $post->title }}">
            </div>

            <!-- Content -->
            <div id="article-body" class="prose-adaptive max-w-none 
                space-y-8 text-lg leading-relaxed">
                {!! nl2br($post->content) !!}
            </div>

            <!-- Footer / Tags -->
            <footer class="pt-12 border-t border-adaptive">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Fitness', 'Health', 'Science', 'Performance'] as $tag)
                            <span class="px-3 py-1.5 rounded-lg bg-adaptive border border-adaptive text-[10px] font-bold text-gray-500 uppercase tracking-widest hover:text-brand hover:border-brand/30 cursor-pointer transition-all">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-black text-gray-500 uppercase tracking-widest">Share:</span>
                        <div class="flex gap-2">
                            @foreach(['twitter', 'facebook', 'linkedin'] as $social)
                                <button class="w-9 h-9 rounded-xl bg-adaptive border border-adaptive flex items-center justify-center text-gray-400 hover:text-brand transition-all shadow-sm">
                                    <span class="sr-only">{{ $social }}</span>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 5.523 4.477 10 10 10s10-4.477 10-10c0-5.523-4.477-10-10-10z"/></svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </footer>
        </article>

        <!-- SIDEBAR (Sticky) -->
        <aside class="lg:col-span-4 space-y-8">
            <div class="sticky top-28 space-y-8">
                
                <!-- Table of Contents -->
                <div class="card p-8 bg-adaptive border border-adaptive shadow-xl">
                    <h3 class="text-sm font-black text-main-area uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Table of Contents
                    </h3>
                    <nav id="toc" class="space-y-4">
                        <!-- Populated via JS -->
                        <div class="animate-pulse space-y-3">
                            <div class="h-2 bg-adaptive rounded w-full"></div>
                            <div class="h-2 bg-adaptive rounded w-3/4"></div>
                            <div class="h-2 bg-adaptive rounded w-5/6"></div>
                        </div>
                    </nav>
                </div>

                <!-- Related Articles -->
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-main-area uppercase tracking-[0.2em] flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        Related Articles
                    </h3>
                    <div class="space-y-4">
                        @foreach($relatedPosts as $rp)
                        <a href="{{ route('blog.show', $rp) }}" class="group flex gap-4 p-3 rounded-2xl bg-adaptive hover:border-brand/30 border border-adaptive transition-all shadow-sm">
                            <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 border border-adaptive">
                                <img src="{{ $rp->image_url ?? 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=200&auto=format&fit=crop' }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="flex flex-col justify-center min-w-0">
                                <h4 class="text-sm font-bold text-main-area group-hover:text-brand transition-colors line-clamp-2 leading-tight mb-1">{{ $rp->title }}</h4>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">{{ \Carbon\Carbon::parse($rp->published_at)->format('M d, Y') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Newsletter / CTA -->
                <div class="card p-8 bg-gradient-to-br from-brand/10 to-brand-dark/5 border border-brand/20 relative overflow-hidden group bg-adaptive">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-brand/10 rounded-full blur-2xl group-hover:bg-brand/20 transition-all"></div>
                    <h3 class="text-lg font-black text-main-area mb-2 leading-tight">Master your fitness journey</h3>
                    <p class="text-xs text-gray-500 mb-6 leading-relaxed">Join 10,000+ members receiving weekly science-backed fitness tips.</p>
                    <button class="w-full bg-brand text-white font-black py-3 rounded-xl hover:bg-brand-dark shadow-lg shadow-brand/20 transition-all text-xs uppercase tracking-widest">
                        Join Newsletter
                    </button>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toc = document.getElementById('toc');
        const content = document.getElementById('article-body');
        const headings = content.querySelectorAll('h2');
        
        if (headings.length > 0) {
            toc.innerHTML = '';
            headings.forEach((heading, index) => {
                const id = `heading-${index}`;
                heading.setAttribute('id', id);
                
                const link = document.createElement('a');
                link.href = `#${id}`;
                link.className = 'block text-xs font-bold text-gray-500 hover:text-brand transition-colors border-l-2 border-transparent hover:border-brand pl-3 py-1';
                link.textContent = heading.textContent;
                
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.getElementById(id).scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
                
                toc.appendChild(link);
            });
        } else {
            toc.parentElement.style.display = 'none';
        }
    });
</script>
@endsection
