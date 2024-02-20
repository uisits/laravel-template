<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';

    public static function canView(): bool
    {
        if(auth()->user()->can('super_admin'))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

}
