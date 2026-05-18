@extends('layouts.dashboard')

@section('page-title', 'Manage Blog')
@section('page-subtitle', 'Publish and edit articles')

@section('content')
<div class="space-y-6 fade-up">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-white">Articles</h2>
            <p class="text-sm text-gray-400 mt-1">Total posts: {{ $posts->count() }}</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="btn-primary flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Write Article
        </a>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 text-xs font-bold text-gray-500 uppercase tracking-widest">
                    <th class="p-6">Title</th>
                    <th class="p-6">Category</th>
                    <th class="p-6">Status</th>
                    <th class="p-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-col/50">
                @foreach($posts as $post)
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="p-6 font-bold text-white">{{ $post->title }}</td>
                        <td class="p-6">
                            <span class="badge-orange">{{ $post->category->name ?? 'Uncategorized' }}</span>
                        </td>
                        <td class="p-6">
                            @if($post->published_at)
                                <span class="badge-green">Published</span>
                            @else
                                <span class="bg-gray-700/30 text-gray-500 px-2.5 py-1 rounded-full text-xs font-bold border border-gray-700/50">Draft</span>
                            @endif
                        </td>
                        <td class="p-6 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.blog.edit', $post) }}" class="text-xs font-bold text-brand hover:underline uppercase tracking-widest">Edit</a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Delete this post?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-500 hover:underline uppercase tracking-widest">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
