<?php

namespace App\Enums\Evaluations;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question6Options: int implements HasLabel
{

    case STRONGLY_DISAGREE = 1;

    case DISAGREE = 2;

    case NEUTRAL = 3;

    case AGREE = 4;

    case STRONGLY_AGREE = 5;

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return Str::of($this->value)
            ->value();
    }
}
