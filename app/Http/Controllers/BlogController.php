<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $query = Post::published()
            ->with(['author', 'category', 'tags'])
            ->latest('published_at');

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by tag if provided
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(9);
        $categories = Category::whereHas('posts', function ($q) {
            $q->published();
        })->withCount(['posts' => function ($q) {
            $q->published();
        }])->get();

        // Get featured post
        $featuredPost = Post::published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->first();

        // Get popular posts
        $popularPosts = Post::published()
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        return view('blog.modern-index', compact('posts', 'categories', 'featuredPost', 'popularPosts'));
    }

    /**
     * Display the specified blog post
     */
    public function show(string $slug)
    {
        $post = Post::published()
            ->with(['author', 'category', 'tags', 'comments' => function ($q) {
                $q->approved()->whereNull('parent_id')->with('replies')->latest();
            }])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        \App\Jobs\IncrementPostViewCount::dispatch($post->id);

        // Get related posts
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->where('category_id', $post->category_id)
                    ->orWhereHas('tags', function ($q) use ($post) {
                        $q->whereIn('tags.id', $post->tags->pluck('id'));
                    });
            })
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.modern-show', compact('post', 'relatedPosts'));
    }
}
