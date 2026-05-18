<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'category'])->latest('published_at');

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('content', 'like', "%{$s}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $posts = $query->paginate(9);
        $categories = BlogCategory::all();
        
        // Featured post (first post of all, if not searching/filtering)
        $featuredPost = null;
        if (!$request->filled('search') && !$request->filled('category')) {
            $featuredPost = $posts->shift();
        }

        return view('blog.index', compact('posts', 'categories', 'featuredPost'));
    }

    public function show(BlogPost $post)
    {
        $post->load(['author', 'category']);
        
        // Related articles (same category, excluding current)
        $relatedPosts = BlogPost::where('blog_category_id', $post->blog_category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
