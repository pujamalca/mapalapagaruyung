<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionParticipant;
use App\Models\Expedition;
use App\Models\ExpeditionParticipant;
use App\Models\TrainingParticipant;
use App\Models\TrainingProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Get user's expedition history
     */
    public function expeditions(Request $request): JsonResponse
    {
        $user = $request->user();

        $expeditions = ExpeditionParticipant::where('user_id', $user->id)
            ->with(['expedition'])
            ->latest('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $expeditions->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'expedition' => [
                        'id' => $participant->expedition->id,
                        'title' => $participant->expedition->title,
                        'slug' => $participant->expedition->slug,
                        'destination' => $participant->expedition->destination,
                        'distance_km' => $participant->expedition->distance_km,
                        'elevation_gain_m' => $participant->expedition->elevation_gain_m,
                        'difficulty_level' => $participant->expedition->difficulty_level,
                        'start_date' => $participant->expedition->start_date->toISOString(),
                        'end_date' => $participant->expedition->end_date?->toISOString(),
                        'status' => $participant->expedition->status,
                    ],
                    'role' => $participant->role,
                    'status' => $participant->status,
                    'performance_rating' => $participant->performance_rating,
                    'performance_notes' => $participant->performance_notes,
                    'registered_at' => $participant->created_at->toISOString(),
                ];
            }),
            'meta' => [
                'current_page' => $expeditions->currentPage(),
                'last_page' => $expeditions->lastPage(),
                'per_page' => $expeditions->perPage(),
                'total' => $expeditions->total(),
            ],
        ]);
    }

    /**
     * Get expedition detail
     */
    public function expeditionDetail(Request $request, Expedition $expedition): JsonResponse
    {
        $user = $request->user();

        $participant = ExpeditionParticipant::where('expedition_id', $expedition->id)
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'expedition' => [
                    'id' => $expedition->id,
                    'title' => $expedition->title,
                    'slug' => $expedition->slug,
                    'description' => $expedition->description,
                    'destination' => $expedition->destination,
                    'checkpoints' => $expedition->checkpoints,
                    'distance_km' => $expedition->distance_km,
                    'elevation_gain_m' => $expedition->elevation_gain_m,
                    'difficulty_level' => $expedition->difficulty_level,
                    'start_date' => $expedition->start_date->toISOString(),
                    'end_date' => $expedition->end_date?->toISOString(),
                    'meeting_point' => $expedition->meeting_point,
                    'meeting_time' => $expedition->meeting_time,
                    'max_participants' => $expedition->max_participants,
                    'total_participants' => $expedition->expeditionParticipants()->count(),
                    'status' => $expedition->status,
                ],
                'participation' => $participant ? [
                    'id' => $participant->id,
                    'role' => $participant->role,
                    'status' => $participant->status,
                    'performance_rating' => $participant->performance_rating,
                    'performance_notes' => $participant->performance_notes,
                    'fitness_verified' => $participant->fitness_verified,
                    'equipment_verified' => $participant->equipment_verified,
                    'payment_verified' => $participant->payment_verified,
                ] : null,
            ],
        ]);
    }

    /**
     * Get user's training history
     */
    public function training(Request $request): JsonResponse
    {
        $user = $request->user();

        $training = TrainingParticipant::where('user_id', $user->id)
            ->with(['trainingProgram'])
            ->latest('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $training->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'training' => [
                        'id' => $participant->trainingProgram->id,
                        'name' => $participant->trainingProgram->name,
                        'description' => $participant->trainingProgram->description,
                        'training_type' => $participant->trainingProgram->training_type,
                        'level' => $participant->trainingProgram->level,
                        'start_date' => $participant->trainingProgram->start_date->toISOString(),
                        'end_date' => $participant->trainingProgram->end_date?->toISOString(),
                        'status' => $participant->trainingProgram->status,
                    ],
                    'status' => $participant->status,
                    'attendance_count' => $participant->attendance_count,
                    'total_sessions' => $participant->total_sessions,
                    'total_score' => $participant->total_score,
                    'certificate_number' => $participant->certificate_number,
                    'certificate_issued_at' => $participant->certificate_issued_at?->toISOString(),
                    'registered_at' => $participant->created_at->toISOString(),
                ];
            }),
            'meta' => [
                'current_page' => $training->currentPage(),
                'last_page' => $training->lastPage(),
                'per_page' => $training->perPage(),
                'total' => $training->total(),
            ],
        ]);
    }

    /**
     * Get user's competition history
     */
    public function competitions(Request $request): JsonResponse
    {
        $user = $request->user();

        $competitions = CompetitionParticipant::where('user_id', $user->id)
            ->with(['competition'])
            ->latest('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $competitions->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'competition' => [
                        'id' => $participant->competition->id,
                        'title' => $participant->competition->title,
                        'slug' => $participant->competition->slug,
                        'event_type' => $participant->competition->event_type,
                        'competition_type' => $participant->competition->competition_type,
                        'location' => $participant->competition->location,
                        'start_date' => $participant->competition->start_date->toISOString(),
                        'end_date' => $participant->competition->end_date?->toISOString(),
                        'status' => $participant->competition->status,
                    ],
                    'status' => $participant->status,
                    'participation_type' => $participant->participation_type,
                    'team_name' => $participant->team_name,
                    'rank' => $participant->rank,
                    'position' => $participant->position,
                    'score' => $participant->score,
                    'medal_type' => $participant->medal_type,
                    'certificate_number' => $participant->certificate_number,
                    'registered_at' => $participant->created_at->toISOString(),
                ];
            }),
            'meta' => [
                'current_page' => $competitions->currentPage(),
                'last_page' => $competitions->lastPage(),
                'per_page' => $competitions->perPage(),
                'total' => $competitions->total(),
            ],
        ]);
    }

    /**
     * Get available activities for registration
     */
    public function available(Request $request): JsonResponse
    {
        // Available expeditions
        $expeditions = Expedition::where('registration_status', 'open')
            ->where('status', 'preparing')
            ->latest('start_date')
            ->take(10)
            ->get()
            ->map(function ($expedition) {
                return [
                    'type' => 'expedition',
                    'id' => $expedition->id,
                    'title' => $expedition->title,
                    'destination' => $expedition->destination,
                    'start_date' => $expedition->start_date->toISOString(),
                    'difficulty_level' => $expedition->difficulty_level,
                    'max_participants' => $expedition->max_participants,
                    'current_participants' => $expedition->expeditionParticipants()->count(),
                    'registration_deadline' => $expedition->registration_deadline?->toISOString(),
                ];
            });

        // Available training
        $training = TrainingProgram::where('registration_status', 'open')
            ->whereIn('status', ['draft', 'scheduled'])
            ->latest('start_date')
            ->take(10)
            ->get()
            ->map(function ($program) {
                return [
                    'type' => 'training',
                    'id' => $program->id,
                    'name' => $program->name,
                    'training_type' => $program->training_type,
                    'level' => $program->level,
                    'start_date' => $program->start_date->toISOString(),
                    'max_participants' => $program->max_participants,
                    'current_participants' => $program->trainingParticipants()->count(),
                    'registration_deadline' => $program->registration_deadline?->toISOString(),
                ];
            });

        // Available competitions
        $competitions = Competition::where('registration_status', 'open')
            ->whereIn('status', ['upcoming', 'registration_open'])
            ->latest('start_date')
            ->take(10)
            ->get()
            ->map(function ($competition) {
                return [
                    'type' => 'competition',
                    'id' => $competition->id,
                    'title' => $competition->title,
                    'event_type' => $competition->event_type,
                    'location' => $competition->location,
                    'start_date' => $competition->start_date->toISOString(),
                    'participation_type' => $competition->participation_type,
                    'registration_deadline' => $competition->registration_deadline?->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'expeditions' => $expeditions,
                'training' => $training,
                'competitions' => $competitions,
            ],
        ]);
    }
}
