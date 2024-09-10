<?php

namespace App\Enums\Evaluations;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question8Options: int implements HasLabel
{

    case INCOMPETENT = 1;

    case SOMEWHAT_INCOMPETENT = 2;

    case SATISFACTORY = 3;

    case COMPETENT = 4;

    case EXCEPTIONALLY_COMPETENT = 5;

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
