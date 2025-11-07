<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Division;
use App\Models\User;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        // Get current leadership (users with role Admin or specific positions)
        $leadership = User::role(['Admin', 'Super Admin'])
            ->where('is_active', true)
            ->take(6)
            ->get();

        // Get all divisions
        $divisions = Division::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get latest cohort
        $latestCohort = Cohort::where('is_active', true)
            ->latest('year')
            ->first();

        // Statistics
        $stats = [
            'total_members' => User::role(['Member', 'Alumni', 'Senior Member'])->count(),
            'active_members' => User::role(['Member', 'Senior Member'])->where('is_active', true)->count(),
            'alumni' => User::role('Alumni')->count(),
            'cohorts' => Cohort::count(),
            'divisions' => Division::where('is_active', true)->count(),
        ];

        return view('pages.about', compact('leadership', 'divisions', 'latestCohort', 'stats'));
    }
}
