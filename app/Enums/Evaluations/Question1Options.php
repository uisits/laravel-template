<?php

namespace App\Enums\Evaluations;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question1Options: string implements HasLabel
{

    case UNDER_GRADUATE = 'under_graduate';

    case GRADUATE = 'graduate';

    case PREFER_NOT_TO_ANSWER = 'prefer_not_to_answer';

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

    /**
     * Retrieve an array of the values from all cases.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
