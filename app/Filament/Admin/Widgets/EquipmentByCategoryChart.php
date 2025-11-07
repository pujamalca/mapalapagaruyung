<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Filament\Widgets\ChartWidget;

class EquipmentByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Peralatan per Kategori';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $categories = EquipmentCategory::withCount([
            'equipment as total_quantity' => function ($query) {
                $query->selectRaw('SUM(quantity)');
            }
        ])->get();

        $labels = [];
        $data = [];
        $backgroundColors = [];
        $colors = [
            'rgba(59, 130, 246, 0.8)',   // Blue
            'rgba(16, 185, 129, 0.8)',   // Green
            'rgba(251, 146, 60, 0.8)',   // Orange
            'rgba(147, 51, 234, 0.8)',   // Purple
            'rgba(236, 72, 153, 0.8)',   // Pink
            'rgba(14, 165, 233, 0.8)',   // Cyan
            'rgba(234, 179, 8, 0.8)',    // Yellow
            'rgba(239, 68, 68, 0.8)',    // Red
        ];

        foreach ($categories as $index => $category) {
            $labels[] = $category->name;
            $data[] = $category->total_quantity ?? 0;
            $backgroundColors[] = $colors[$index % count($colors)];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Unit',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
