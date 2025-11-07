<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Cohort;
use App\Models\Division;
use Filament\Widgets\ChartWidget;

class MemberDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Anggota';

    protected static ?int $sort = 7;

    public ?string $filter = 'cohort';

    protected function getData(): array
    {
        if ($this->filter === 'cohort') {
            return $this->getCohortDistribution();
        } else {
            return $this->getDivisionDistribution();
        }
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'cohort' => 'Per Angkatan',
            'division' => 'Per Divisi',
        ];
    }

    private function getCohortDistribution(): array
    {
        $cohorts = Cohort::withCount('members')
            ->orderBy('name')
            ->get();

        $labels = [];
        $data = [];

        foreach ($cohorts as $cohort) {
            $labels[] = $cohort->name;
            $data[] = $cohort->members_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Anggota',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    private function getDivisionDistribution(): array
    {
        $divisions = Division::withCount('users')
            ->orderBy('name')
            ->get();

        $labels = [];
        $data = [];

        foreach ($divisions as $division) {
            $labels[] = $division->name;
            $data[] = $division->users_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Anggota',
                    'data' => $data,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
