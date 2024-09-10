<?php

namespace App\Enums\Evaluations;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum TeacherQualityOptions: int implements HasLabel
{

    case POOR = 1;

    case FAIR = 2;

    case GOOD = 3;

    case VERY_GOOD = 4;

    case EXCELLENT = 5;

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
