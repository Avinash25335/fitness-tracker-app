<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    // ─── Public: List all posts ───────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'category'])
            ->withCount(['likes', 'comments'])
            ->latest('published_at');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('content', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $posts      = $query->paginate(12);
        $categories = BlogCategory::all();

        // Attach liked status for the authenticated user
        $likedIds = [];
        if (Auth::check()) {
            $likedIds = BlogLike::where('user_id', Auth::id())
                ->whereIn('blog_post_id', $posts->pluck('id'))
                ->pluck('blog_post_id')
                ->toArray();
        }

        return view('blog.index', compact('posts', 'categories', 'likedIds'));
    }

    // ─── Public: Read a single post ──────────────────────────────────────────
    public function show(BlogPost $post)
    {
        $post->load(['author', 'category', 'comments.user'])->loadCount(['likes', 'comments']);

        $relatedPosts = BlogPost::where('blog_category_id', $post->blog_category_id)
            ->where('id', '!=', $post->id)
            ->withCount(['likes', 'comments'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $isLiked = Auth::check() ? $post->isLikedByUser(Auth::id()) : false;

        return view('blog.show', compact('post', 'relatedPosts', 'isLiked'));
    }

    // ─── Auth: Show create post form ─────────────────────────────────────────
    public function create()
    {
        $categories = BlogCategory::all();
        return view('blog.create', compact('categories'));
    }

    // ─── Auth: Store new post ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string|min:20',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'image_url'        => 'nullable|url|max:500',
            'excerpt'          => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($validated['title']);
        $original = $slug;
        $counter  = 1;
        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $counter++;
        }

        BlogPost::create([
            'user_id'          => Auth::id(),
            'title'            => $validated['title'],
            'slug'             => $slug,
            'content'          => $validated['content'],
            'excerpt'          => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160),
            'blog_category_id' => $validated['blog_category_id'] ?? null,
            'image_url'        => $validated['image_url'] ?? null,
            'published_at'     => now(),
        ]);

        return redirect()->route('blog.index')->with('success', '🎉 Your post is live!');
    }

    // ─── Auth: Toggle like ────────────────────────────────────────────────────
    public function like(BlogPost $post)
    {
        $userId   = Auth::id();
        $existing = BlogLike::where('blog_post_id', $post->id)->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            BlogLike::create(['blog_post_id' => $post->id, 'user_id' => $userId]);
            $liked = true;
        }

        $count = $post->likes()->count();

        return response()->json(['liked' => $liked, 'count' => $count]);
    }

    // ─── Auth: Post a comment ─────────────────────────────────────────────────
    public function comment(Request $request, BlogPost $post)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $comment = BlogComment::create([
            'blog_post_id' => $post->id,
            'user_id'      => Auth::id(),
            'body'         => $request->body,
        ]);

        $comment->load('user');

        if ($request->expectsJson()) {
            return response()->json([
                'id'         => $comment->id,
                'body'       => $comment->body,
                'user'       => $comment->user->name,
                'initial'    => strtoupper(substr($comment->user->name, 0, 1)),
                'created_at' => $comment->created_at->diffForHumans(),
                'can_delete' => true,
                'delete_url' => route('blog.comment.delete', $comment),
            ]);
        }

        return back()->with('success', 'Comment posted!');
    }

    // ─── Auth: Delete own comment ─────────────────────────────────────────────
    public function deleteComment(BlogComment $comment)
    {
        abort_if($comment->user_id !== Auth::id(), 403);
        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(['deleted' => true]);
        }

        return back()->with('success', 'Comment deleted.');
    }
}
