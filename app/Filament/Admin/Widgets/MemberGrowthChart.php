<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MemberGrowthChart extends ChartWidget
{
    protected ?string $heading = 'Pertumbuhan Anggota';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = $this->getMemberGrowthData();

        return [
            'datasets' => [
                [
                    'label' => 'Total Anggota',
                    'data' => $data['totals'],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'fill' => true,
                ],
                [
                    'label' => 'Anggota Baru',
                    'data' => $data['new'],
                    'backgroundColor' => 'rgba(251, 146, 60, 0.5)',
                    'borderColor' => 'rgb(251, 146, 60)',
                    'fill' => false,
                    'borderDash' => [5, 5],
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getMemberGrowthData(): array
    {
        $months = [];
        $labels = [];
        $new = [];
        $totals = [];

        // Get last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date;
            $labels[] = $date->format('M Y');

            // Count new members in this month
            $newCount = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $new[] = $newCount;

            // Count total members up to this month
            $totalCount = User::where('created_at', '<=', $date->endOfMonth())
                ->count();
            $totals[] = $totalCount;
        }

        return [
            'labels' => $labels,
            'new' => $new,
            'totals' => $totals,
        ];
    }
}
