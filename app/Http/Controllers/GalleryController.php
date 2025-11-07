<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Gallery::with(['galleryCategory'])
            ->where('status', 'published')
            ->where('is_public', true);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('gallery_category_id', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $galleries = $query->latest('published_at')->paginate(12);
        $categories = GalleryCategory::orderBy('order')->get();

        return view('pages.modern-gallery', compact('galleries', 'categories'));
    }

    public function show(Gallery $gallery): View
    {
        // Check if gallery is published and public
        if ($gallery->status !== 'published' || !$gallery->is_public) {
            abort(404);
        }

        // Increment view count
        $gallery->increment('view_count');

        // Get related galleries
        $relatedGalleries = Gallery::where('id', '!=', $gallery->id)
            ->where('status', 'published')
            ->where('is_public', true)
            ->where(function ($query) use ($gallery) {
                $query->where('gallery_category_id', $gallery->gallery_category_id)
                      ->orWhere('galleryable_type', $gallery->galleryable_type);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.gallery-detail', compact('gallery', 'relatedGalleries'));
    }
}
