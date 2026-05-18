@extends('layouts.dashboard')

@section('page-title', 'Create Post')
@section('page-subtitle', 'Share your fitness story with the community')

@section('content')
<div class="fade-up max-w-3xl mx-auto">

    {{-- Back --}}
    <div class="mb-8">
        <a href="{{ route('blog.index') }}"
           class="group inline-flex items-center gap-2 text-gray-500 hover:text-brand transition-colors text-sm font-bold uppercase tracking-widest">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Feed
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-adaptive border border-adaptive rounded-3xl shadow-2xl overflow-hidden">

        {{-- Header Banner --}}
        <div class="h-2 bg-gradient-to-r from-brand via-purple-500 to-pink-500"></div>

        <div class="p-8 lg:p-12">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-black text-lg shadow-lg shadow-brand/30">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-black text-main-area">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Creating new post</p>
                </div>
            </div>

            <form action="{{ route('blog.store') }}" method="POST" id="create-post-form" class="space-y-6">
                @csrf

                {{-- Title --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Post Title *</label>
                    <input type="text"
                           id="post-title"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Give your post a great title..."
                           required
                           class="w-full px-5 py-4 rounded-2xl bg-adaptive border border-adaptive focus:border-brand focus:ring-2 focus:ring-brand/20 text-main-area placeholder-gray-600 font-bold text-lg outline-none transition-all">
                    @error('title')
                    <p class="mt-1.5 text-xs text-rose-400 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Category</label>
                    <select id="post-category"
                            name="blog_category_id"
                            class="w-full px-5 py-4 rounded-2xl bg-adaptive border border-adaptive focus:border-brand focus:ring-2 focus:ring-brand/20 text-main-area font-bold outline-none transition-all appearance-none">
                        <option value="">-- No category --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('blog_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('blog_category_id')
                    <p class="mt-1.5 text-xs text-rose-400 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image URL --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Image URL</label>
                    <div class="relative">
                        <input type="url"
                               id="post-image-url"
                               name="image_url"
                               value="{{ old('image_url') }}"
                               placeholder="https://example.com/your-image.jpg"
                               oninput="previewImage(this.value)"
                               class="w-full px-5 py-4 rounded-2xl bg-adaptive border border-adaptive focus:border-brand focus:ring-2 focus:ring-brand/20 text-main-area placeholder-gray-600 font-medium outline-none transition-all">
                    </div>
                    @error('image_url')
                    <p class="mt-1.5 text-xs text-rose-400 font-bold">{{ $message }}</p>
                    @enderror
                    {{-- Preview --}}
                    <div id="image-preview-wrap" class="mt-3 hidden rounded-2xl overflow-hidden border border-adaptive aspect-video">
                        <img id="image-preview" src="" class="w-full h-full object-cover" alt="Preview">
                    </div>
                </div>

                {{-- Content --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Your Story *</label>
                    <textarea id="post-content"
                              name="content"
                              rows="10"
                              required
                              placeholder="Share your fitness journey, tips, experiences..."
                              class="w-full px-5 py-4 rounded-2xl bg-adaptive border border-adaptive focus:border-brand focus:ring-2 focus:ring-brand/20 text-main-area placeholder-gray-600 font-medium outline-none transition-all resize-none leading-relaxed">{{ old('content') }}</textarea>
                    <div class="flex justify-between mt-1.5">
                        @error('content')
                        <p class="text-xs text-rose-400 font-bold">{{ $message }}</p>
                        @else
                        <span></span>
                        @enderror
                        <span id="char-counter" class="text-xs text-gray-500 font-bold">0 characters</span>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-4 pt-4 border-t border-adaptive">
                    <button type="submit"
                            id="submit-post-btn"
                            class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-brand text-white font-black text-sm uppercase tracking-widest shadow-xl shadow-brand/30 hover:bg-brand-dark hover:scale-[1.02] active:scale-95 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Publish Post
                    </button>
                    <a href="{{ route('blog.index') }}"
                       class="px-6 py-4 rounded-2xl bg-adaptive border border-adaptive text-gray-500 font-black text-sm uppercase tracking-widest hover:text-main-area transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(url) {
    const wrap    = document.getElementById('image-preview-wrap');
    const preview = document.getElementById('image-preview');
    if (url && url.startsWith('http')) {
        preview.src = url;
        wrap.classList.remove('hidden');
        preview.onerror = () => wrap.classList.add('hidden');
    } else {
        wrap.classList.add('hidden');
    }
}

// Character counter
const contentEl = document.getElementById('post-content');
const counterEl = document.getElementById('char-counter');
contentEl.addEventListener('input', () => {
    const n = contentEl.value.length;
    counterEl.textContent = n.toLocaleString() + ' character' + (n !== 1 ? 's' : '');
    counterEl.classList.toggle('text-brand', n >= 20);
});
contentEl.dispatchEvent(new Event('input'));

// Prevent double submit
document.getElementById('create-post-form').addEventListener('submit', function () {
    const btn = document.getElementById('submit-post-btn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/><path fill="currentColor" d="M4 12a8 8 0 018-8v8z" class="opacity-75"/></svg> Publishing…`;
});
</script>
@endsection
