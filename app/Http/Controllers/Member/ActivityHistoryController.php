<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CompetitionParticipant;
use App\Models\EquipmentBorrowing;
use App\Models\ExpeditionParticipant;
use App\Models\TrainingParticipant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $user = auth()->user();
        $type = $request->get('type', 'all');

        $expeditions = collect();
        $training = collect();
        $competitions = collect();
        $equipment = collect();

        if ($type === 'all' || $type === 'expedition') {
            $expeditions = ExpeditionParticipant::where('user_id', $user->id)
                ->with(['expedition'])
                ->latest('created_at')
                ->paginate(10);
        }

        if ($type === 'all' || $type === 'training') {
            $training = TrainingParticipant::where('user_id', $user->id)
                ->with(['trainingProgram'])
                ->latest('created_at')
                ->paginate(10);
        }

        if ($type === 'all' || $type === 'competition') {
            $competitions = CompetitionParticipant::where('user_id', $user->id)
                ->with(['competition'])
                ->latest('created_at')
                ->paginate(10);
        }

        if ($type === 'equipment') {
            $equipment = EquipmentBorrowing::where('user_id', $user->id)
                ->with(['equipment'])
                ->latest('borrow_date')
                ->paginate(10);
        }

        return view('member.activity-history', compact('expeditions', 'training', 'competitions', 'equipment', 'type'));
    }
}
