<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;

enum Question3Options: string
{
    case PASS = 'pass/credit';

    case FAIL = 'fail/no_credit';
}
