<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ToolsWidget extends Widget
{
    protected static string $view = 'filament.widgets.tools-widget';

    public static function canView(): bool
    {
        if(auth()->user()->can('super_admin'))
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }
}
