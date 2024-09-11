<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\RegistrationResource\Pages\ListRegistrations;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin']);
    }

    protected function getTablePage(): string
    {
        return ListRegistrations::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();
        return [
            Stat::make('Total Courses', $query->distinct('course_no')->count())
                ->color('warning')
                ->description('Unique Courses for the semester'),
            Stat::make('Total instructors', $query->distinct('instructor_uin')->count())
                ->color('success')
                ->description('Unique Instructors for the semester'),
            Stat::make('Total Registrations', $query->count())
                ->color('info')
                ->description('consists of students registrations for courses.'),
        ];
    }
}
