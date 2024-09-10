<?php

namespace App\Filament\Resources\RegistrationResource\Widgets;

use App\Filament\Resources\RegistrationResource\Pages\ListRegistrations;
use App\Models\Registration;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrationOverview extends BaseWidget implements HasForms
{
    use InteractsWithPageTable;

    use InteractsWithForms;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListRegistrations::class;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('course_no')
                    ->label('Course')
                    ->searchable()
                    ->preload()
                    ->options(Registration::distinct('course_no')->pluck('course_no', 'course_name'))
            ]);
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Registrations', $this->getPageTableQuery()->count()),
        ];
    }
}
