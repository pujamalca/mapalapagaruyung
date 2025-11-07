<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Equipment;
use App\Models\Expedition;
use App\Models\Gallery;
use App\Models\Recruitment;
use App\Models\TrainingProgram;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Get statistics
        $stats = [
            'members' => User::role(['Member', 'Alumni', 'Senior Member'])->count(),
            'expeditions' => Expedition::where('status', 'completed')->count(),
            'competitions' => Competition::whereIn('status', ['completed', 'ongoing'])->count(),
            'equipment' => Equipment::where('status', 'available')->sum('quantity_available'),
            'training_programs' => TrainingProgram::whereIn('status', ['completed', 'ongoing'])->count(),
        ];

        // Get recent expeditions
        $recentExpeditions = Expedition::with(['user'])
            ->whereIn('status', ['completed', 'ongoing'])
            ->latest('start_date')
            ->take(3)
            ->get();

        // Get recent competitions
        $recentCompetitions = Competition::with(['user'])
            ->whereIn('status', ['completed', 'ongoing'])
            ->latest('start_date')
            ->take(3)
            ->get();

        // Get recent training programs
        $recentTraining = TrainingProgram::whereIn('status', ['completed', 'ongoing'])
            ->latest('start_date')
            ->take(3)
            ->get();

        // Get featured galleries
        $featuredGalleries = Gallery::where('is_featured', true)
            ->where('status', 'published')
            ->where('is_public', true)
            ->with('galleryCategory')
            ->latest('published_at')
            ->take(6)
            ->get();

        // Get active recruitment if any
        $activeRecruitment = Recruitment::where('status', 'open')
            ->where('registration_end_date', '>=', now())
            ->first();

        return view('home', compact(
            'stats',
            'recentExpeditions',
            'recentCompetitions',
            'recentTraining',
            'featuredGalleries',
            'activeRecruitment'
        ));
    }
}
