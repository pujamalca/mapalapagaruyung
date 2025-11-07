<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Get galleries
     */
    public function index(Request $request): JsonResponse
    {
        $query = Gallery::where('status', 'published')
            ->where('is_public', true);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('gallery_category_id', $request->category_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Featured only
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        $galleries = $query->with(['galleryCategory:id,name,color'])
            ->latest('published_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'slug' => $gallery->slug,
                    'description' => $gallery->description,
                    'category' => $gallery->galleryCategory ? [
                        'id' => $gallery->galleryCategory->id,
                        'name' => $gallery->galleryCategory->name,
                        'color' => $gallery->galleryCategory->color,
                    ] : null,
                    'is_featured' => $gallery->is_featured,
                    'view_count' => $gallery->view_count,
                    'published_at' => $gallery->published_at?->toISOString(),
                    'created_at' => $gallery->created_at->toISOString(),
                ];
            }),
            'meta' => [
                'current_page' => $galleries->currentPage(),
                'last_page' => $galleries->lastPage(),
                'per_page' => $galleries->perPage(),
                'total' => $galleries->total(),
            ],
        ]);
    }

    /**
     * Get gallery detail
     */
    public function show(Gallery $gallery): JsonResponse
    {
        if ($gallery->status !== 'published' || !$gallery->is_public) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery not found',
            ], 404);
        }

        // Increment view count
        $gallery->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'slug' => $gallery->slug,
                'description' => $gallery->description,
                'category' => $gallery->galleryCategory ? [
                    'id' => $gallery->galleryCategory->id,
                    'name' => $gallery->galleryCategory->name,
                    'color' => $gallery->galleryCategory->color,
                ] : null,
                'event_date' => $gallery->event_date?->toISOString(),
                'location' => $gallery->location,
                'photographer' => $gallery->photographer,
                'tags' => $gallery->tags,
                'is_featured' => $gallery->is_featured,
                'view_count' => $gallery->view_count,
                'published_at' => $gallery->published_at?->toISOString(),
                'related_activity' => $gallery->galleryable ? [
                    'type' => class_basename($gallery->galleryable_type),
                    'id' => $gallery->galleryable_id,
                ] : null,
            ],
        ]);
    }

    /**
     * Get gallery categories
     */
    public function categories(): JsonResponse
    {
        $categories = GalleryCategory::orderBy('order')->get();

        return response()->json([
            'success' => true,
            'data' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'icon' => $category->icon,
                    'color' => $category->color,
                    'gallery_count' => $category->galleries()
                        ->where('status', 'published')
                        ->where('is_public', true)
                        ->count(),
                ];
            }),
        ]);
    }
}
