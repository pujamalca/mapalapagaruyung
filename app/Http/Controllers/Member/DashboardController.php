<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CompetitionParticipant;
use App\Models\EquipmentBorrowing;
use App\Models\ExpeditionParticipant;
use App\Models\TrainingParticipant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = auth()->user();

        // Get participation stats
        $stats = [
            'expeditions' => ExpeditionParticipant::where('user_id', $user->id)->count(),
            'expeditions_completed' => ExpeditionParticipant::where('user_id', $user->id)
                ->whereIn('status', ['completed', 'passed'])
                ->count(),
            'training' => TrainingParticipant::where('user_id', $user->id)->count(),
            'training_completed' => TrainingParticipant::where('user_id', $user->id)
                ->whereIn('status', ['completed', 'passed'])
                ->count(),
            'competitions' => CompetitionParticipant::where('user_id', $user->id)->count(),
            'medals' => CompetitionParticipant::where('user_id', $user->id)
                ->whereNotNull('medal_type')
                ->count(),
            'equipment_borrowed' => EquipmentBorrowing::where('user_id', $user->id)->count(),
            'equipment_active' => EquipmentBorrowing::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'active'])
                ->count(),
        ];

        // Recent expeditions
        $recentExpeditions = ExpeditionParticipant::where('user_id', $user->id)
            ->with(['expedition'])
            ->latest('created_at')
            ->take(5)
            ->get();

        // Recent training
        $recentTraining = TrainingParticipant::where('user_id', $user->id)
            ->with(['trainingProgram'])
            ->latest('created_at')
            ->take(5)
            ->get();

        // Recent competitions
        $recentCompetitions = CompetitionParticipant::where('user_id', $user->id)
            ->with(['competition'])
            ->latest('created_at')
            ->take(5)
            ->get();

        // Active equipment borrowings
        $activeEquipment = EquipmentBorrowing::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->with(['equipment'])
            ->latest('borrow_date')
            ->take(5)
            ->get();

        // Overdue equipment
        $overdueEquipment = EquipmentBorrowing::where('user_id', $user->id)
            ->where('status', '!=', 'returned')
            ->where('due_date', '<', now())
            ->with(['equipment'])
            ->get();

        return view('member.dashboard', compact(
            'stats',
            'recentExpeditions',
            'recentTraining',
            'recentCompetitions',
            'activeEquipment',
            'overdueEquipment'
        ));
    }
}
