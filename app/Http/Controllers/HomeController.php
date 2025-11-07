<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Equipment;
use App\Models\Expedition;
use App\Models\Gallery;
use App\Models\RecruitmentPeriod;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Support\RoleMapper;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $memberRoles = RoleMapper::normalize(['Member', 'Alumni', 'Senior Member']);

        // Get statistics
        $stats = [
            'members' => User::role($memberRoles)->count(),
            'expeditions' => Expedition::where('status', 'completed')->count(),
            'competitions' => Competition::whereIn('status', ['completed', 'ongoing'])->count(),
            'equipment' => Equipment::where('status', 'available')->sum('quantity_available'),
            'training_programs' => TrainingProgram::whereIn('status', ['completed', 'ongoing'])->count(),
        ];

        // Get recent expeditions
        $recentExpeditions = Expedition::with(['leader'])
            ->whereIn('status', ['completed', 'ongoing'])
            ->latest('start_date')
            ->take(3)
            ->get();

        // Get recent competitions
        $recentCompetitions = Competition::with(['coordinator'])
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
        $featuredGalleries = Gallery::query()
            ->featured()
            ->published()
            ->public()
            ->with('galleryCategory')
            ->orderByDesc('published_at')
            ->orderByDesc('event_date')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        // Get active recruitment if any
        $activeRecruitment = RecruitmentPeriod::where('status', 'open')
            ->where('registration_end', '>=', now())
            ->first();

        // Add training stat for consistency
        $stats['training'] = $stats['training_programs'];

        return view('modern-home', compact(
            'stats',
            'recentExpeditions',
            'recentCompetitions',
            'recentTraining',
            'featuredGalleries',
            'activeRecruitment'
        ));
    }
}
