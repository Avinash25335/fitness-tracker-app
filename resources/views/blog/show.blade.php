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

    {{-- Reading Progress Bar --}}
    <div class="fixed top-16 left-0 w-full h-1 z-50 bg-white/5 lg:left-64 lg:w-[calc(100%-16rem)]">
        <div class="h-full bg-gradient-to-r from-brand to-pink-500 transition-all duration-75" :style="`width: ${scrollProgress}%`"></div>
    </div>

    {{-- Back --}}
    <div class="mb-10">
        <a href="{{ route('blog.index') }}" class="group inline-flex items-center gap-2 text-gray-500 hover:text-brand transition-colors text-sm font-bold uppercase tracking-widest">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Feed
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

        {{-- ── MAIN ARTICLE ──────────────────────────────────────────────────── --}}
        <article class="lg:col-span-8 space-y-10">

            {{-- Header --}}
            <header class="space-y-6">
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-lg bg-brand/10 text-brand text-[10px] font-black uppercase tracking-widest border border-brand/20">
                        {{ $post->category->name ?? 'Fitness' }}
                    </span>
                    <span class="text-gray-600">•</span>
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">⏱ {{ $post->read_time }} min read</span>
                </div>

                <h1 class="text-4xl lg:text-5xl font-black text-main-area leading-[1.1] tracking-tight">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center justify-between gap-6 pt-4 border-t border-adaptive">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-black text-lg">
                            {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-main-area">{{ $post->author->name ?? 'FitCore User' }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                                {{ \Carbon\Carbon::parse($post->published_at)->format('F d, Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- ── Like Button (Hero) ──────────────────────────────── --}}
                    <div class="flex items-center gap-4">
                        <button
                            id="hero-like-btn"
                            data-post-id="{{ $post->id }}"
                            data-liked="{{ $isLiked ? 'true' : 'false' }}"
                            data-like-url="{{ route('blog.like', $post) }}"
                            onclick="toggleLike(this)"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-full border transition-all duration-200 font-black text-sm hover:scale-105
                                   {{ $isLiked ? 'bg-rose-500/10 border-rose-500/30 text-rose-500' : 'bg-adaptive border-adaptive text-gray-500 hover:border-rose-400/30 hover:text-rose-400' }}">
                            <svg class="w-5 h-5 transition-all" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span id="hero-like-count">{{ $post->likes_count }}</span>
                            {{ $isLiked ? 'Liked' : 'Like' }}
                        </button>

                        <a href="#comments" class="flex items-center gap-2 px-5 py-2.5 rounded-full border border-adaptive bg-adaptive text-gray-500 hover:text-brand hover:border-brand/30 transition-all font-black text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ $post->comments_count }} Comments
                        </a>
                    </div>
                </div>
            </header>

            {{-- Hero Image --}}
            <div class="rounded-3xl overflow-hidden shadow-2xl border border-adaptive aspect-video">
                <img src="{{ $post->image_url ?? 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=1200&auto=format&fit=crop' }}"
                     class="w-full h-full object-cover" alt="{{ $post->title }}">
            </div>

            {{-- Article Content --}}
            <div id="article-body" class="prose-adaptive max-w-none space-y-8 text-lg leading-relaxed">
                {!! nl2br(e($post->content)) !!}
            </div>

            {{-- Tags / Share Footer --}}
            <footer class="pt-12 border-t border-adaptive">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Fitness', 'Health', 'Science', 'Performance'] as $tag)
                        <span class="px-3 py-1.5 rounded-lg bg-adaptive border border-adaptive text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                            #{{ $tag }}
                        </span>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="toggleLike(document.getElementById('hero-like-btn'))"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-adaptive border border-adaptive text-gray-500 hover:text-rose-400 hover:border-rose-400/30 transition-all text-xs font-black">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Like this post
                        </button>
                    </div>
                </div>
            </footer>

            {{-- ── COMMENTS SECTION ──────────────────────────────────────────── --}}
            <section id="comments" class="pt-4 space-y-8">
                <h3 class="text-xl font-black text-main-area flex items-center gap-3">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Comments
                    <span id="comments-total" class="text-sm font-bold text-gray-500">({{ $post->comments_count }})</span>
                </h3>

                {{-- Comment Form --}}
                @auth
                <div class="bg-adaptive border border-adaptive rounded-2xl p-5">
                    <div class="flex gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-black text-sm shrink-0 mt-1">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 space-y-3">
                            <textarea id="comment-body"
                                      rows="3"
                                      placeholder="Add a comment..."
                                      class="w-full px-4 py-3 rounded-xl bg-adaptive border border-adaptive focus:border-brand focus:ring-2 focus:ring-brand/20 text-main-area placeholder-gray-600 text-sm font-medium outline-none transition-all resize-none"></textarea>
                            <div class="flex justify-end">
                                <button id="post-comment-btn"
                                        onclick="postComment()"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-brand/20 hover:bg-brand-dark hover:scale-105 active:scale-95 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Post Comment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-adaptive border border-adaptive rounded-2xl p-6 text-center">
                    <p class="text-gray-500 font-medium mb-4">You must be logged in to leave a comment.</p>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-brand/20 hover:bg-brand-dark transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login to Comment
                    </a>
                </div>
                @endauth

                {{-- Comments List --}}
                <div id="comments-list" class="space-y-4">
                    @forelse($post->comments as $comment)
                    <div class="comment-item flex gap-3 bg-adaptive border border-adaptive rounded-2xl p-4" id="comment-{{ $comment->id }}">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center text-white font-black text-sm shrink-0 mt-0.5">
                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="text-sm font-black text-main-area">{{ $comment->user->name ?? 'User' }}</span>
                                    <span class="text-[10px] text-gray-500 font-bold ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                @auth
                                @if(Auth::id() === $comment->user_id)
                                <button onclick="deleteComment({{ $comment->id }}, '{{ route('blog.comment.delete', $comment) }}')"
                                        class="text-gray-600 hover:text-rose-400 transition-colors text-xs font-bold shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                @endif
                                @endauth
                            </div>
                            <p class="text-sm text-gray-400 mt-1 leading-relaxed font-medium">{{ $comment->body }}</p>
                        </div>
                    </div>
                    @empty
                    <p id="no-comments-msg" class="text-center text-gray-500 font-medium py-8">No comments yet. Be the first!</p>
                    @endforelse
                </div>
            </section>
        </article>

        {{-- ── SIDEBAR ─────────────────────────────────────────────────────── --}}
        <aside class="lg:col-span-4 space-y-8">
            <div class="sticky top-28 space-y-8">

                {{-- Author Card --}}
                <div class="card p-6 bg-adaptive border border-adaptive shadow-xl text-center rounded-3xl">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-black text-2xl mx-auto mb-4 shadow-lg shadow-brand/30">
                        {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                    </div>
                    <p class="font-black text-main-area">{{ $post->author->name ?? 'FitCore User' }}</p>
                    <p class="text-xs text-gray-500 font-bold mt-1">Community Member</p>
                    <div class="flex justify-center gap-6 mt-4 pt-4 border-t border-adaptive">
                        <div class="text-center">
                            <p class="text-lg font-black text-main-area">{{ $post->likes_count }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Likes</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-black text-main-area">{{ $post->comments_count }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Comments</p>
                        </div>
                    </div>
                </div>

                {{-- Table of Contents --}}
                <div class="card p-8 bg-adaptive border border-adaptive shadow-xl rounded-3xl">
                    <h3 class="text-sm font-black text-main-area uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Contents
                    </h3>
                    <nav id="toc" class="space-y-3">
                        <div class="animate-pulse space-y-3">
                            <div class="h-2 bg-adaptive rounded w-full"></div>
                            <div class="h-2 bg-adaptive rounded w-3/4"></div>
                            <div class="h-2 bg-adaptive rounded w-5/6"></div>
                        </div>
                    </nav>
                </div>

                {{-- Related Posts --}}
                @if($relatedPosts->isNotEmpty())
                <div class="space-y-4">
                    <h3 class="text-sm font-black text-main-area uppercase tracking-[0.2em] flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        More Posts
                    </h3>
                    @foreach($relatedPosts as $rp)
                    <a href="{{ route('blog.show', $rp) }}" class="group flex gap-3 p-3 rounded-2xl bg-adaptive hover:border-brand/30 border border-adaptive transition-all shadow-sm">
                        <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-adaptive">
                            <img src="{{ $rp->image_url ?? 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=200&auto=format&fit=crop' }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="flex flex-col justify-center min-w-0">
                            <h4 class="text-xs font-bold text-main-area group-hover:text-brand transition-colors line-clamp-2 leading-tight mb-1">{{ $rp->title }}</h4>
                            <div class="flex items-center gap-3 text-[10px] text-gray-500 font-bold">
                                <span>❤ {{ $rp->likes_count }}</span>
                                <span>💬 {{ $rp->comments_count }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

            </div>
        </aside>
    </div>
</div>

<script>
const isAuth     = {{ Auth::check() ? 'true' : 'false' }};
const loginUrl   = "{{ route('login') }}";
const csrfToken  = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const commentUrl = "{{ route('blog.comment', $post) }}";

// ── Like toggle ──────────────────────────────────────────────────────────────
function toggleLike(btn) {
    if (!isAuth) { window.location.href = loginUrl; return; }

    const liked    = btn.dataset.liked === 'true';
    const countEl  = document.getElementById('hero-like-count');
    const heartSvg = btn.querySelector('svg');

    btn.dataset.liked = (!liked).toString();
    const newLiked = !liked;

    heartSvg.setAttribute('fill', newLiked ? 'currentColor' : 'none');
    btn.classList.toggle('text-rose-500', newLiked);
    btn.classList.toggle('bg-rose-500/10', newLiked);
    btn.classList.toggle('border-rose-500/30', newLiked);
    btn.classList.add('scale-110');
    setTimeout(() => btn.classList.remove('scale-110'), 200);

    fetch(btn.dataset.likeUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        countEl.textContent = data.count;
        btn.dataset.liked   = data.liked.toString();
        heartSvg.setAttribute('fill', data.liked ? 'currentColor' : 'none');
    });
}

// ── Post comment ─────────────────────────────────────────────────────────────
function postComment() {
    const body    = document.getElementById('comment-body');
    const btn     = document.getElementById('post-comment-btn');
    const list    = document.getElementById('comments-list');
    const noMsg   = document.getElementById('no-comments-msg');
    const total   = document.getElementById('comments-total');

    if (!body.value.trim()) { body.focus(); return; }

    btn.disabled = true;
    btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/><path fill="currentColor" d="M4 12a8 8 0 018-8v8z" class="opacity-75"/></svg> Posting…`;

    fetch(commentUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ body: body.value.trim() }),
    })
    .then(r => r.json())
    .then(data => {
        if (noMsg) noMsg.remove();

        const html = `
        <div class="comment-item flex gap-3 bg-adaptive border border-adaptive rounded-2xl p-4 animate-pulse-once" id="comment-${data.id}" style="animation: fadeSlide 0.3s ease-out">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-black text-sm shrink-0 mt-0.5">
                ${data.initial}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="text-sm font-black text-main-area">${data.user}</span>
                        <span class="text-[10px] text-gray-500 font-bold ml-2">${data.created_at}</span>
                    </div>
                    <button onclick="deleteComment(${data.id}, '${data.delete_url}')"
                            class="text-gray-600 hover:text-rose-400 transition-colors text-xs font-bold shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-400 mt-1 leading-relaxed font-medium">${data.body}</p>
            </div>
        </div>`;

        list.insertAdjacentHTML('afterbegin', html);

        // Update total count
        const match = total.textContent.match(/\d+/);
        const n = match ? parseInt(match[0]) + 1 : 1;
        total.textContent = `(${n})`;

        body.value = '';
        btn.disabled = false;
        btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Post Comment`;
    });
}

// ── Delete comment ────────────────────────────────────────────────────────────
function deleteComment(id, url) {
    const el = document.getElementById('comment-' + id);
    if (!el) return;

    fetch(url, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        if (data.deleted) {
            el.style.transition = 'opacity 0.3s, transform 0.3s';
            el.style.opacity    = '0';
            el.style.transform  = 'translateX(-10px)';
            setTimeout(() => {
                el.remove();
                const total = document.getElementById('comments-total');
                const match = total.textContent.match(/\d+/);
                const n = match ? Math.max(0, parseInt(match[0]) - 1) : 0;
                total.textContent = `(${n})`;
                if (!document.querySelector('.comment-item')) {
                    document.getElementById('comments-list').innerHTML =
                        '<p id="no-comments-msg" class="text-center text-gray-500 font-medium py-8">No comments yet. Be the first!</p>';
                }
            }, 300);
        }
    });
}

// ── Table of Contents ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const toc      = document.getElementById('toc');
    const content  = document.getElementById('article-body');
    const headings = content.querySelectorAll('h2');

    if (headings.length > 0) {
        toc.innerHTML = '';
        headings.forEach((heading, i) => {
            const id  = `heading-${i}`;
            heading.setAttribute('id', id);
            const link = document.createElement('a');
            link.href      = `#${id}`;
            link.className = 'block text-xs font-bold text-gray-500 hover:text-brand transition-colors border-l-2 border-transparent hover:border-brand pl-3 py-1';
            link.textContent = heading.textContent;
            link.addEventListener('click', e => {
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

<style>
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
