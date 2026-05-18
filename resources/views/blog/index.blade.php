@extends('layouts.dashboard')

@section('page-title', 'Community Feed')
@section('page-subtitle', 'Posts from the FitCore community')

@section('content')
<div class="space-y-8 fade-up">

    {{-- ── TOP BAR ─────────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-main-area tracking-tight">Community Feed</h2>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Share your fitness journey</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Category Pills --}}
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('blog.index', ['search' => request('search')]) }}"
                   class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest transition-all
                          {{ !request('category') ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-adaptive text-gray-500 hover:text-main-area border border-adaptive' }}">
                    All
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('blog.index', ['category' => $cat->slug, 'search' => request('search')]) }}"
                   class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest transition-all
                          {{ request('category') === $cat->slug ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-adaptive text-gray-500 hover:text-main-area border border-adaptive' }}">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>

            @auth
            {{-- New Post Button --}}
            <a href="{{ route('blog.create') }}"
               id="btn-new-post"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-brand text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-brand/30 hover:bg-brand-dark hover:scale-105 transition-all duration-200 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                New Post
            </a>
            @else
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-adaptive border border-adaptive text-gray-500 text-xs font-black uppercase tracking-widest hover:text-brand hover:border-brand/30 transition-all duration-200 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Login to Post
            </a>
            @endauth
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-bold">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ── INSTAGRAM GRID ───────────────────────────────────────────────────── --}}
    @if($posts->isNotEmpty())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $post)
        {{-- POST CARD --}}
        <div class="group bg-adaptive border border-adaptive rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:border-brand/30 hover:-translate-y-1 transition-all duration-300 flex flex-col">

            {{-- Image --}}
            <a href="{{ route('blog.show', $post) }}" class="block relative h-64 overflow-hidden shrink-0 bg-gray-800">
                <img src="{{ $post->image_url ?? 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=600&auto=format&fit=crop' }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                     alt="{{ $post->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                {{-- Category Badge --}}
                <div class="absolute top-3 left-3">
                    <span class="px-2.5 py-1 rounded-lg bg-black/50 backdrop-blur-md border border-white/10 text-white text-[9px] font-black uppercase tracking-widest">
                        {{ $post->category->name ?? 'Fitness' }}
                    </span>
                </div>
            </a>

            {{-- Body --}}
            <div class="p-5 flex flex-col flex-1">
                {{-- Author Row --}}
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-black text-sm shrink-0">
                        {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-black text-main-area truncate">{{ $post->author->name ?? 'FitCore User' }}</p>
                        <p class="text-[10px] text-gray-500 font-bold">{{ \Carbon\Carbon::parse($post->published_at)->diffForHumans() }}</p>
                    </div>
                </div>

                {{-- Title --}}
                <a href="{{ route('blog.show', $post) }}" class="block">
                    <h3 class="text-base font-black text-main-area leading-snug mb-2 line-clamp-2 group-hover:text-brand transition-colors">
                        {{ $post->title }}
                    </h3>
                </a>

                {{-- Excerpt --}}
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 flex-1 font-medium">
                    {{ Str::limit(strip_tags($post->content), 100) }}
                </p>

                {{-- ── Action Bar ──────────────────────────────────────── --}}
                <div class="flex items-center justify-between pt-4 mt-4 border-t border-adaptive">
                    {{-- Like Button --}}
                    <button
                        id="like-btn-{{ $post->id }}"
                        data-post-id="{{ $post->id }}"
                        data-liked="{{ in_array($post->id, $likedIds) ? 'true' : 'false' }}"
                        data-like-url="{{ route('blog.like', $post) }}"
                        onclick="toggleLike(this)"
                        class="like-btn flex items-center gap-1.5 text-xs font-black transition-all duration-200 hover:scale-110
                               {{ in_array($post->id, $likedIds) ? 'text-rose-500' : 'text-gray-500 hover:text-rose-400' }}">
                        <svg class="w-5 h-5 transition-all" fill="{{ in_array($post->id, $likedIds) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span id="like-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                    </button>

                    {{-- Comment Count --}}
                    <a href="{{ route('blog.show', $post) }}#comments"
                       class="flex items-center gap-1.5 text-xs font-black text-gray-500 hover:text-brand transition-all hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        {{ $post->comments_count }}
                    </a>

                    {{-- Read More --}}
                    <a href="{{ route('blog.show', $post) }}"
                       class="text-brand text-xs font-black flex items-center gap-1 hover:gap-2 transition-all">
                        Read <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-10 flex justify-center border-t border-adaptive pt-8">
        {{ $posts->links() }}
    </div>

    @else
    {{-- Empty State --}}
    <div class="card p-20 text-center flex flex-col items-center bg-adaptive border-adaptive shadow-2xl">
        <div class="w-20 h-20 rounded-3xl bg-adaptive border border-adaptive flex items-center justify-center mb-6 shadow-inner">
            <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <h3 class="text-2xl font-black text-main-area mb-2 tracking-tight">No posts yet</h3>
        <p class="text-gray-500 mb-8 max-w-sm leading-relaxed font-medium">Be the first to share your fitness journey with the community.</p>
        @auth
        <a href="{{ route('blog.create') }}" class="bg-brand text-white font-black px-8 py-3.5 rounded-xl hover:bg-brand-dark transition-all shadow-lg shadow-brand/20">
            Create First Post
        </a>
        @else
        <a href="{{ route('login') }}" class="bg-brand text-white font-black px-8 py-3.5 rounded-xl hover:bg-brand-dark transition-all shadow-lg shadow-brand/20">
            Login to Post
        </a>
        @endauth
    </div>
    @endif

</div>

{{-- ── LIKE TOGGLE SCRIPT ─────────────────────────────────────────────────── --}}
<script>
const loginUrl = "{{ route('login') }}";
const isAuth   = {{ Auth::check() ? 'true' : 'false' }};
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function toggleLike(btn) {
    if (!isAuth) {
        window.location.href = loginUrl;
        return;
    }

    const postId   = btn.dataset.postId;
    const liked    = btn.dataset.liked === 'true';
    const url      = btn.dataset.likeUrl;
    const countEl  = document.getElementById('like-count-' + postId);
    const heartSvg = btn.querySelector('svg');

    // Optimistic UI
    const newLiked = !liked;
    btn.dataset.liked = newLiked ? 'true' : 'false';
    btn.classList.toggle('text-rose-500', newLiked);
    btn.classList.toggle('text-gray-500', !newLiked);
    heartSvg.setAttribute('fill', newLiked ? 'currentColor' : 'none');
    btn.classList.add('scale-125');
    setTimeout(() => btn.classList.remove('scale-125'), 200);

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        countEl.textContent = data.count;
        btn.dataset.liked   = data.liked ? 'true' : 'false';
        btn.classList.toggle('text-rose-500', data.liked);
        btn.classList.toggle('text-gray-500', !data.liked);
        heartSvg.setAttribute('fill', data.liked ? 'currentColor' : 'none');
    })
    .catch(() => {
        // Revert on error
        btn.dataset.liked = liked ? 'true' : 'false';
        btn.classList.toggle('text-rose-500', liked);
        btn.classList.toggle('text-gray-500', !liked);
        heartSvg.setAttribute('fill', liked ? 'currentColor' : 'none');
    });
}
</script>
@endsection
