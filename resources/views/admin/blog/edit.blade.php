@extends('layouts.dashboard')

@section('page-title', 'Edit Article')
@section('page-subtitle', 'Refine your content for the community')

@section('content')
<div class="max-w-4xl mx-auto fade-up">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.blog.index') }}" class="w-10 h-10 rounded-xl bg-surface-2 border border-border-col flex items-center justify-center text-gray-400 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="text-2xl font-extrabold text-white">Edit: {{ $blog->title }}</h2>
    </div>

    <div class="card p-8">
        <form action="{{ route('admin.blog.update', $blog) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Post Title</label>
                        <input type="text" name="title" value="{{ old('title', $blog->title) }}" required
                               class="input-field">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Category</label>
                        <select name="blog_category_id" class="input-field">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $blog->blog_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Slug</label>
                        <input type="text" value="{{ $blog->slug }}" disabled
                               class="input-field opacity-50 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Update Banner</label>
                    <div class="relative h-full group/upload">
                        <input type="file" name="image" accept="image/*"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="h-[210px] border-2 border-dashed border-border-col rounded-2xl flex flex-col items-center justify-center text-center p-6 hover:border-brand hover:bg-white/5 transition-all duration-300">
                            @if($blog->image_url)
                                <img src="{{ asset('storage/' . $blog->image_url) }}" class="h-24 w-auto rounded-lg mb-2 object-cover">
                                <p class="text-[10px] text-brand font-bold uppercase">Click to replace image</p>
                            @else
                                <svg class="w-10 h-10 text-gray-600 mb-3 group-hover/upload:text-brand transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-sm text-gray-500 font-semibold">Click to upload banner</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Content</label>
                <textarea name="content" rows="12" class="input-field">{{ old('content', $blog->content) }}</textarea>
            </div>

            <div class="pt-6 border-t border-border-col flex items-center justify-end gap-4">
                <a href="{{ route('admin.blog.index') }}" class="btn-ghost">Cancel</a>
                <button type="submit" class="btn-primary px-8">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
