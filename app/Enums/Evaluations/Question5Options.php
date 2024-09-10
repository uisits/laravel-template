<?php

namespace App\Enums\Evaluations;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question5Options: int implements HasLabel
{

    case DECREASED = 1;

    case REMAINED_SAME = 2;

    case INCREASED = 3;

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return Str::of($this->name)
            ->replace('_', ' ')
            ->lower()
            ->ucfirst()
            ->value();
    }
}
