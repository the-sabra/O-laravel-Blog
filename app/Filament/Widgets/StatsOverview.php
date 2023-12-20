<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected static ?string $pollingInterval= '25s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts',Post::count())
            ->descriptionIcon('heroicon-0-arrow-trending-up')
            ->color('success')
            ->chart([2,8,3,6,4,8]),
            Stat::make('Total Authors',User::where('role','author')->count())
        ];
    }
}
