<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Competition;
use App\Models\Equipment;
use App\Models\EquipmentBorrowing;
use App\Models\Expedition;
use App\Models\TrainingProgram;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $activeMembers = User::role(['Member', 'Senior Member'])->count();
        $alumni = User::role('Alumni')->count();

        $totalEquipment = Equipment::sum('quantity_total');
        $availableEquipment = Equipment::sum('quantity_available');
        $equipmentUtilization = $totalEquipment > 0
            ? round((($totalEquipment - $availableEquipment) / $totalEquipment) * 100, 1)
            : 0;

        $ongoingExpeditions = Expedition::whereIn('status', ['ongoing', 'preparation'])->count();
        $completedExpeditions = Expedition::where('status', 'completed')
            ->whereYear('end_date', now()->year)
            ->count();

        $ongoingTraining = TrainingProgram::whereIn('status', ['ongoing', 'open_registration'])->count();
        $completedTraining = TrainingProgram::where('status', 'completed')
            ->whereYear('end_date', now()->year)
            ->count();

        $pendingBorrowings = EquipmentBorrowing::where('status', 'pending')->count();
        $overdueBorrowings = EquipmentBorrowing::where('status', '!=', 'returned')
            ->where('due_date', '<', now())
            ->count();

        $upcomingCompetitions = Competition::where('status', 'upcoming')
            ->where('start_date', '>', now())
            ->where('start_date', '<=', now()->addDays(30))
            ->count();

        return [
            Stat::make('Anggota Aktif', $activeMembers)
                ->description($alumni . ' Alumni')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([12, 15, 18, 22, 25, 28, $activeMembers]),

            Stat::make('Utilitas Peralatan', $equipmentUtilization . '%')
                ->description($availableEquipment . ' dari ' . $totalEquipment . ' unit tersedia')
                ->descriptionIcon('heroicon-m-cube')
                ->color($equipmentUtilization > 70 ? 'success' : ($equipmentUtilization > 40 ? 'warning' : 'danger'))
                ->chart([30, 45, 55, 60, 65, 68, $equipmentUtilization]),

            Stat::make('Ekspedisi Tahun Ini', $completedExpeditions)
                ->description($ongoingExpeditions . ' sedang berlangsung')
                ->descriptionIcon('heroicon-m-map')
                ->color('primary')
                ->chart([2, 4, 6, 8, 10, 12, $completedExpeditions]),

            Stat::make('Program Pelatihan', $completedTraining)
                ->description($ongoingTraining . ' sedang berjalan')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info')
                ->chart([1, 2, 3, 4, 5, 6, $completedTraining]),

            Stat::make('Peminjaman Pending', $pendingBorrowings)
                ->description($overdueBorrowings > 0 ? $overdueBorrowings . ' terlambat!' : 'Tidak ada yang terlambat')
                ->descriptionIcon($overdueBorrowings > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($overdueBorrowings > 0 ? 'danger' : ($pendingBorrowings > 0 ? 'warning' : 'success')),

            Stat::make('Kompetisi Mendatang', $upcomingCompetitions)
                ->description('30 hari ke depan')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning')
                ->chart([0, 1, 1, 2, 2, 3, $upcomingCompetitions]),
        ];
    }

    protected static ?int $sort = 1;
}
