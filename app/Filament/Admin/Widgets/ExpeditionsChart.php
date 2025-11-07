<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Expedition;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ExpeditionsChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Ekspedisi per Bulan';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'year';

    protected function getData(): array
    {
        $data = $this->getExpeditionsPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Ekspedisi',
                    'data' => $data['counts'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'year' => 'Tahun Ini',
            'all' => 'Semua Waktu',
            '6months' => '6 Bulan Terakhir',
        ];
    }

    private function getExpeditionsPerMonth(): array
    {
        $query = Expedition::query()
            ->select(
                DB::raw('YEAR(start_date) as year'),
                DB::raw('MONTH(start_date) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc');

        if ($this->filter === 'year') {
            $query->whereYear('start_date', now()->year);
        } elseif ($this->filter === '6months') {
            $query->where('start_date', '>=', now()->subMonths(6));
        }

        $results = $query->get();

        $labels = [];
        $counts = [];

        if ($this->filter === 'year') {
            // Generate all months for current year
            for ($i = 1; $i <= 12; $i++) {
                $monthName = date('M', mktime(0, 0, 0, $i, 1));
                $labels[] = $monthName;

                $count = $results->where('month', $i)->first()?->count ?? 0;
                $counts[] = $count;
            }
        } else {
            // Use actual data
            foreach ($results as $result) {
                $monthName = date('M Y', mktime(0, 0, 0, $result->month, 1, $result->year));
                $labels[] = $monthName;
                $counts[] = $result->count;
            }
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
        ];
    }
}
