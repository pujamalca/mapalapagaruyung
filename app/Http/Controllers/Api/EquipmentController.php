<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentBorrowing;
use App\Models\EquipmentCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Get available equipment
     */
    public function index(Request $request): JsonResponse
    {
        $query = Equipment::where('status', 'available')
            ->where('quantity_available', '>', 0);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('equipment_category_id', $request->category_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $equipment = $query->with('equipmentCategory:id,name')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $equipment->map(function ($item) {
                return [
                    'id' => $item->id,
                    'code' => $item->code,
                    'name' => $item->name,
                    'category' => [
                        'id' => $item->equipmentCategory->id,
                        'name' => $item->equipmentCategory->name,
                    ],
                    'brand' => $item->brand,
                    'model' => $item->model,
                    'quantity_available' => $item->quantity_available,
                    'unit' => $item->unit,
                    'condition' => $item->condition,
                    'description' => $item->description,
                ];
            }),
            'meta' => [
                'current_page' => $equipment->currentPage(),
                'last_page' => $equipment->lastPage(),
                'per_page' => $equipment->perPage(),
                'total' => $equipment->total(),
            ],
        ]);
    }

    /**
     * Get equipment categories
     */
    public function categories(): JsonResponse
    {
        $categories = EquipmentCategory::where('is_active', true)
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'icon' => $category->icon,
                    'equipment_count' => $category->getEquipmentCount(),
                ];
            }),
        ]);
    }

    /**
     * Get user's borrowing history
     */
    public function borrowings(Request $request): JsonResponse
    {
        $user = $request->user();

        $borrowings = EquipmentBorrowing::where('user_id', $user->id)
            ->with(['equipment:id,code,name,unit'])
            ->latest('borrow_date')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $borrowings->map(function ($borrowing) {
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
                    'return_date' => $borrowing->return_date?->toISOString(),
                    'status' => $borrowing->status,
                    'purpose' => $borrowing->purpose,
                    'is_late' => $borrowing->is_late,
                    'days_late' => $borrowing->days_late,
                    'penalty_amount' => $borrowing->penalty_amount,
                    'is_damaged' => $borrowing->is_damaged,
                    'damage_cost' => $borrowing->damage_cost,
                ];
            }),
            'meta' => [
                'current_page' => $borrowings->currentPage(),
                'last_page' => $borrowings->lastPage(),
                'per_page' => $borrowings->perPage(),
                'total' => $borrowings->total(),
            ],
        ]);
    }

    /**
     * Get active borrowings
     */
    public function activeBorrowings(Request $request): JsonResponse
    {
        $user = $request->user();

        $borrowings = EquipmentBorrowing::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->with(['equipment:id,code,name,unit'])
            ->latest('borrow_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $borrowings->map(function ($borrowing) {
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
                    'days_until_due' => $borrowing->due_date->diffInDays(now(), false),
                    'potential_penalty' => $borrowing->isOverdue() ? $borrowing->calculatePenalty() : 0,
                ];
            }),
        ]);
    }

    /**
     * Request equipment borrowing
     */
    public function requestBorrowing(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'equipment_id' => ['required', 'exists:equipment,id'],
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'due_date' => ['required', 'date', 'after:borrow_date'],
            'quantity_borrowed' => ['required', 'integer', 'min:1'],
            'purpose' => ['required', 'string', 'in:expedition,training,competition,event,personal,other'],
            'purpose_details' => ['nullable', 'string', 'max:500'],
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);

        // Check availability
        if ($equipment->quantity_available < $validated['quantity_borrowed']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient equipment available. Only ' . $equipment->quantity_available . ' ' . $equipment->unit . ' available.',
            ], 400);
        }

        // Create borrowing request
        $borrowing = EquipmentBorrowing::create([
            'borrowing_code' => EquipmentBorrowing::generateBorrowingCode(),
            'equipment_id' => $validated['equipment_id'],
            'user_id' => $request->user()->id,
            'borrow_date' => $validated['borrow_date'],
            'due_date' => $validated['due_date'],
            'quantity_borrowed' => $validated['quantity_borrowed'],
            'purpose' => $validated['purpose'],
            'purpose_details' => $validated['purpose_details'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Borrowing request submitted successfully. Waiting for approval.',
            'data' => [
                'id' => $borrowing->id,
                'borrowing_code' => $borrowing->borrowing_code,
                'status' => $borrowing->status,
            ],
        ], 201);
    }
}
