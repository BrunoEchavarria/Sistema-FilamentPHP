<?php

namespace App\Filament\Widgets;

use App\Models\Holiday;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;

use function PHPSTORM_META\type;

class Stats extends BaseWidget
{
    protected static ?int $navigationSort = 1;
    protected function getStats(): array
    {
        
        return [
            Stat::make('Timesheet type', Timesheet::query()->where('type', ['work', 'pause'])->count()),
            Stat::make('Employes', User::query()->count()),
            Stat::make('Holidays approved', Holiday::query()->where('type', 'approved')->count()),
        ];
    }
}
