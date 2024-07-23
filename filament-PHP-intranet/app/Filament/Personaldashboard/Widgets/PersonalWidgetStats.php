<?php

namespace App\Filament\Personaldashboard\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PersonalWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending holidays', $this->getPendingHoliday(Auth::user())),
            Stat::make('Approve holidays', $this->getApproveHoliday(Auth::user())),
            Stat::make('Total work', $this->getTotalWork(Auth::user())),
        ];
    }

    protected function getPendingHoliday(User $user){
        $totalPendingHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'pending')->get()->count();

            return $totalPendingHolidays;
    }
    protected function getApproveHoliday(User $user){
        $totalApproveHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'approved')->get()->count();

            return $totalApproveHolidays;
    }
    protected function getTotalWork(User $user){
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'work')->get();
        $sumHours = 0;
        foreach($timesheets as $timesheet){
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);

            $totalDuration = $finishTime->diffInSeconds($startTime);
            $sumHours = $sumHours + $totalDuration;
        }

        $tiempoFormato = gmdate("H:i:s", $sumHours);

        return $tiempoFormato;
    }
}
