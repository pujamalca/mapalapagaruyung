<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostPreviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// About, Gallery, Activities, Contact routes
Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/gallery', [App\Http\Controllers\GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{gallery}', [App\Http\Controllers\GalleryController::class, 'show'])->name('gallery.show');
Route::get('/activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/expedition/{expedition}', [App\Http\Controllers\ActivityController::class, 'showExpedition'])->name('activities.expedition');
Route::get('/activities/competition/{competition}', [App\Http\Controllers\ActivityController::class, 'showCompetition'])->name('activities.competition');
Route::get('/activities/training/{training}', [App\Http\Controllers\ActivityController::class, 'showTraining'])->name('activities.training');
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Pages routes
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

// Recruitment routes
Route::get('/recruitment', \App\Livewire\RegistrationForm::class)->name('recruitment.register');
Route::get('/recruitment/success/{registration_number}', function ($registration_number) {
    return view('recruitment.success', compact('registration_number'));
})->name('recruitment.success');

// Guest Routes (Registration & Password Reset)
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])
        ->middleware('throttle:6,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->name('password.update');
});

// Email Verification Routes (Authenticated Users)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');
});

// Member Portal (protected with auth middleware)
Route::middleware('auth')->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Member\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Member\ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [App\Http\Controllers\Member\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Member\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Member\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/activities', [App\Http\Controllers\Member\ActivityHistoryController::class, 'index'])->name('activities');
});

// Preview routes (protected with auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/preview/posts/{post}', [PostPreviewController::class, 'show'])->name('posts.preview');
    Route::get('/preview/pages/{page}', [PageController::class, 'preview'])->name('pages.preview');
});
