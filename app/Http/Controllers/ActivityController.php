<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Expedition;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->get('type', 'all');

        $expeditions = collect();
        $competitions = collect();
        $trainings = collect();

        if ($type === 'all' || $type === 'expedition') {
            $expeditions = Expedition::with(['user'])
                ->whereIn('status', ['completed', 'ongoing', 'preparing'])
                ->latest('start_date')
                ->take($type === 'expedition' ? 12 : 4)
                ->get();
        }

        if ($type === 'all' || $type === 'competition') {
            $competitions = Competition::with(['user'])
                ->whereIn('status', ['completed', 'ongoing', 'upcoming'])
                ->latest('start_date')
                ->take($type === 'competition' ? 12 : 4)
                ->get();
        }

        if ($type === 'all' || $type === 'training') {
            $trainings = TrainingProgram::whereIn('status', ['completed', 'ongoing', 'scheduled'])
                ->latest('start_date')
                ->take($type === 'training' ? 12 : 4)
                ->get();
        }

        return view('pages.activities', compact('expeditions', 'competitions', 'trainings', 'type'));
    }

    public function showExpedition(Expedition $expedition): View
    {
        $expedition->load(['user', 'expeditionParticipants.user']);

        return view('pages.expedition-detail', compact('expedition'));
    }

    public function showCompetition(Competition $competition): View
    {
        $competition->load(['user', 'competitionParticipants.user']);

        return view('pages.competition-detail', compact('competition'));
    }

    public function showTraining(TrainingProgram $training): View
    {
        $training->load(['trainingParticipants.user', 'trainingSessions']);

        return view('pages.training-detail', compact('training'));
    }
}
