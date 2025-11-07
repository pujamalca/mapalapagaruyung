<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\EquipmentByCategoryChart;
use App\Filament\Admin\Widgets\ExpeditionsChart;
use App\Filament\Admin\Widgets\MemberDistributionChart;
use App\Filament\Admin\Widgets\MemberGrowthChart;
use App\Filament\Admin\Widgets\PendingBorrowingsWidget;
use App\Filament\Admin\Widgets\RecentExpeditionsWidget;
use App\Filament\Admin\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static string $view = 'filament.admin.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            ExpeditionsChart::class,
            MemberGrowthChart::class,
            RecentExpeditionsWidget::class,
            PendingBorrowingsWidget::class,
            EquipmentByCategoryChart::class,
            MemberDistributionChart::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }
}
