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
            $expeditions = Expedition::with(['leader'])
                ->whereIn('status', ['completed', 'ongoing', 'preparing'])
                ->latest('start_date')
                ->take($type === 'expedition' ? 12 : 4)
                ->get();
        }

        if ($type === 'all' || $type === 'competition') {
            $competitions = Competition::with(['coordinator'])
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

        // Prepare calendar events data
        $calendarEvents = [];

        foreach ($expeditions as $expedition) {
            $calendarEvents[] = [
                'title' => $expedition->name,
                'start' => $expedition->start_date,
                'end' => $expedition->end_date,
                'type' => 'expedition',
                'status' => $expedition->status,
                'url' => route('activities.expedition', $expedition),
            ];
        }

        foreach ($competitions as $competition) {
            $calendarEvents[] = [
                'title' => $competition->name,
                'start' => $competition->start_date,
                'end' => $competition->end_date,
                'type' => 'competition',
                'status' => $competition->status,
                'url' => route('activities.competition', $competition),
            ];
        }

        foreach ($trainings as $training) {
            $calendarEvents[] = [
                'title' => $training->name,
                'start' => $training->start_date,
                'end' => $training->end_date,
                'type' => 'training',
                'status' => $training->status,
                'url' => route('activities.training', $training),
            ];
        }

        return view('pages.modern-activities', compact('expeditions', 'competitions', 'trainings', 'type', 'calendarEvents'));
    }

    public function showExpedition(Expedition $expedition): View
    {
        $expedition->load(['leader', 'expeditionParticipants.user']);

        return view('pages.expedition-detail', compact('expedition'));
    }

    public function showCompetition(Competition $competition): View
    {
        $competition->load(['coordinator', 'competitionParticipants.user']);

        return view('pages.competition-detail', compact('competition'));
    }

    public function showTraining(TrainingProgram $training): View
    {
        $training->load(['trainingParticipants.user', 'trainingSessions']);

        return view('pages.training-detail', compact('training'));
    }
}
