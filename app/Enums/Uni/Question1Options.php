<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question1Options: int implements HasLabel
{

    case UNDER_GRADUATE = 1;

    case GRADUATE = 2;

    case PREFER_NOT_TO_ANSWER = 0;

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
