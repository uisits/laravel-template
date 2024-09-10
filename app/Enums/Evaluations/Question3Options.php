<?php

namespace App\Enums\Evaluations;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question3Options: string implements HasLabel
{
    case MORE_THAN_EXPECTED = 'more_than_expected';

    case AS_EXPECTED = 'as_expected';

    case LESS_THAN_EXPECTED = 'less_than_expected';

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
