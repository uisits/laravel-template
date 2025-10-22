<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected string $view = 'filament.widgets.welcome-widget';

    public static function canView(): bool
    {
        return true;
    }
}
