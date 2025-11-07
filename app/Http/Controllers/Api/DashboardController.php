<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompetitionParticipant;
use App\Models\EquipmentBorrowing;
use App\Models\ExpeditionParticipant;
use App\Models\TrainingParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Statistics
        $stats = [
            'expeditions' => [
                'total' => ExpeditionParticipant::where('user_id', $user->id)->count(),
                'completed' => ExpeditionParticipant::where('user_id', $user->id)
                    ->whereIn('status', ['completed', 'passed'])
                    ->count(),
            ],
            'training' => [
                'total' => TrainingParticipant::where('user_id', $user->id)->count(),
                'completed' => TrainingParticipant::where('user_id', $user->id)
                    ->whereIn('status', ['completed', 'passed'])
                    ->count(),
            ],
            'competitions' => [
                'total' => CompetitionParticipant::where('user_id', $user->id)->count(),
                'medals' => CompetitionParticipant::where('user_id', $user->id)
                    ->whereNotNull('medal_type')
                    ->count(),
            ],
            'equipment' => [
                'total_borrowed' => EquipmentBorrowing::where('user_id', $user->id)->count(),
                'active' => EquipmentBorrowing::where('user_id', $user->id)
                    ->whereIn('status', ['approved', 'active'])
                    ->count(),
                'overdue' => EquipmentBorrowing::where('user_id', $user->id)
                    ->where('status', '!=', 'returned')
                    ->where('due_date', '<', now())
                    ->count(),
            ],
        ];

        // Recent expeditions
        $recentExpeditions = ExpeditionParticipant::where('user_id', $user->id)
            ->with(['expedition:id,title,destination,start_date,end_date,status'])
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'expedition' => [
                        'id' => $participant->expedition->id,
                        'title' => $participant->expedition->title,
                        'destination' => $participant->expedition->destination,
                        'start_date' => $participant->expedition->start_date->toISOString(),
                        'end_date' => $participant->expedition->end_date?->toISOString(),
                        'status' => $participant->expedition->status,
                    ],
                    'role' => $participant->role,
                    'status' => $participant->status,
                    'performance_rating' => $participant->performance_rating,
                ];
            });

        // Recent training
        $recentTraining = TrainingParticipant::where('user_id', $user->id)
            ->with(['trainingProgram:id,name,description,start_date,end_date,status'])
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'training' => [
                        'id' => $participant->trainingProgram->id,
                        'name' => $participant->trainingProgram->name,
                        'description' => $participant->trainingProgram->description,
                        'start_date' => $participant->trainingProgram->start_date->toISOString(),
                        'end_date' => $participant->trainingProgram->end_date?->toISOString(),
                        'status' => $participant->trainingProgram->status,
                    ],
                    'status' => $participant->status,
                    'total_score' => $participant->total_score,
                    'certificate_number' => $participant->certificate_number,
                ];
            });

        // Active equipment borrowings
        $activeEquipment = EquipmentBorrowing::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->with(['equipment:id,code,name,unit'])
            ->latest('borrow_date')
            ->get()
            ->map(function ($borrowing) {
                return [
                    'id' => $borrowing->id,
                    'borrowing_code' => $borrowing->borrowing_code,
                    'equipment' => [
                        'id' => $borrowing->equipment->id,
                        'code' => $borrowing->equipment->code,
                        'name' => $borrowing->equipment->name,
                        'unit' => $borrowing->equipment->unit,
                    ],
                    'quantity_borrowed' => $borrowing->quantity_borrowed,
                    'borrow_date' => $borrowing->borrow_date->toISOString(),
                    'due_date' => $borrowing->due_date->toISOString(),
                    'status' => $borrowing->status,
                    'is_overdue' => $borrowing->isOverdue(),
                    'days_late' => $borrowing->isOverdue() ? $borrowing->getDaysLate() : 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $stats,
                'recent_activities' => [
                    'expeditions' => $recentExpeditions,
                    'training' => $recentTraining,
                ],
                'active_equipment' => $activeEquipment,
            ],
        ]);
    }
}
