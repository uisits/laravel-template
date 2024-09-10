<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question2Options: int implements HasLabel
{

    case FEMALE = 1;

    case MALE = 2;

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
