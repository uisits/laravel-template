<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\Widget;

class EvaluationWidget extends Widget
{
    protected static string $view = 'filament.widgets.evaluation-widget';

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        if(Registration::where('student_netid', auth()->user()->netid)->exists())
        {
            return true;
        }
        return false;
    }

}
