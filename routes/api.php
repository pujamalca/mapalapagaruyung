<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PostController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Simple search endpoint for landing page
Route::get('/search', function (Request $request) {
    $query = $request->get('q', '');

    if (strlen($query) < 2) {
        return response()->json(['data' => []]);
    }

    $posts = Post::query()
        ->where('status', 'published')
        ->where(function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('excerpt', 'LIKE', "%{$query}%")
              ->orWhere('content', 'LIKE', "%{$query}%");
        })
        ->with('category')
        ->orderBy('published_at', 'desc')
        ->limit(10)
        ->get()
        ->map(function($post) {
            return [
                'slug' => $post->slug,
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'category' => $post->category->name ?? 'Uncategorized',
                'date' => $post->published_at?->format('d M Y') ?? '',
            ];
        });

    return response()->json(['data' => $posts]);
})->middleware('throttle:60,1');

Route::prefix('v1')
    ->name('api.v1.')
    ->middleware(['api'])
    ->group(function (): void {
        Route::prefix('auth')->group(function (): void {
            Route::post('register', [AuthController::class, 'register'])->name('auth.register');
            Route::post('login', [AuthController::class, 'login'])->name('auth.login');

            Route::middleware(['auth:sanctum', 'active'])->group(function (): void {
                Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
                Route::get('profile', [AuthController::class, 'profile'])->name('auth.profile');
            });
        });

        Route::get('posts', [PostController::class, 'index'])
            ->middleware('throttle:public-content')
            ->name('posts.index');
        Route::get('posts/{post:slug}', [PostController::class, 'show'])
            ->middleware('throttle:public-content')
            ->name('posts.show');

        Route::get('pages', [PageController::class, 'index'])
            ->middleware('throttle:public-content')
            ->name('pages.index');
        Route::get('pages/{slug}', [PageController::class, 'show'])
            ->middleware('throttle:public-content')
            ->name('pages.show');

        Route::middleware(['auth:sanctum', 'active', 'abilities:posts:write', 'throttle:content-write'])->group(function (): void {
            Route::post('posts', [PostController::class, 'store'])->name('posts.store');
            Route::match(['put', 'patch'], 'posts/{post}', [PostController::class, 'update'])->name('posts.update');
            Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        });

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('categories/{category:slug}/posts', [CategoryController::class, 'posts'])->name('categories.posts');

        Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
        Route::get('posts/{post:slug}/comments', [CommentController::class, 'forPost'])->name('posts.comments.index');
        Route::post('posts/{post:slug}/comments', [CommentController::class, 'store'])
            ->middleware('throttle:comments')
            ->name('posts.comments.store');

        Route::middleware(['auth:sanctum', 'active', 'abilities:comments:moderate'])->group(function (): void {
            Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
            Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
        });
    });

// Mapala-specific API endpoints
Route::prefix('v1/mapala')
    ->name('api.mapala.')
    ->middleware(['api'])
    ->group(function (): void {
        // Authentication
        Route::prefix('auth')->group(function (): void {
            Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register'])->name('auth.register');
            Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login'])->name('auth.login');

            Route::middleware(['auth:sanctum'])->group(function (): void {
                Route::post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->name('auth.logout');
                Route::get('me', [App\Http\Controllers\Api\AuthController::class, 'me'])->name('auth.me');
                Route::put('profile', [App\Http\Controllers\Api\AuthController::class, 'updateProfile'])->name('auth.profile.update');
                Route::put('password', [App\Http\Controllers\Api\AuthController::class, 'changePassword'])->name('auth.password.change');
            });
        });

        // Protected routes
        Route::middleware(['auth:sanctum'])->group(function (): void {
            // Dashboard
            Route::get('dashboard', [App\Http\Controllers\Api\DashboardController::class, 'index'])->name('dashboard');

            // Activities
            Route::prefix('activities')->group(function (): void {
                Route::get('available', [App\Http\Controllers\Api\ActivityController::class, 'available'])->name('activities.available');
                Route::get('expeditions', [App\Http\Controllers\Api\ActivityController::class, 'expeditions'])->name('activities.expeditions');
                Route::get('expeditions/{expedition}', [App\Http\Controllers\Api\ActivityController::class, 'expeditionDetail'])->name('activities.expeditions.show');
                Route::get('training', [App\Http\Controllers\Api\ActivityController::class, 'training'])->name('activities.training');
                Route::get('competitions', [App\Http\Controllers\Api\ActivityController::class, 'competitions'])->name('activities.competitions');
            });

            // Equipment
            Route::prefix('equipment')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Api\EquipmentController::class, 'index'])->name('equipment.index');
                Route::get('categories', [App\Http\Controllers\Api\EquipmentController::class, 'categories'])->name('equipment.categories');
                Route::get('borrowings', [App\Http\Controllers\Api\EquipmentController::class, 'borrowings'])->name('equipment.borrowings');
                Route::get('borrowings/active', [App\Http\Controllers\Api\EquipmentController::class, 'activeBorrowings'])->name('equipment.borrowings.active');
                Route::post('borrowings', [App\Http\Controllers\Api\EquipmentController::class, 'requestBorrowing'])->name('equipment.borrowings.request');
            });

            // Gallery
            Route::prefix('gallery')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Api\GalleryController::class, 'index'])->name('gallery.index');
                Route::get('categories', [App\Http\Controllers\Api\GalleryController::class, 'categories'])->name('gallery.categories');
                Route::get('{gallery}', [App\Http\Controllers\Api\GalleryController::class, 'show'])->name('gallery.show');
            });
        });
    });
