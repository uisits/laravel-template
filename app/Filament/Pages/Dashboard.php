<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\WelcomeWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            WelcomeWidget::class,
        ];
    }

    public function getHeading(): string
    {
        return 'Dashboard';
    }
}
