<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question4Options: string implements HasLabel
{
    case VERY_TIMELY = "very_timely";

    case MODERATELY_TIMELY = "moderately_timely";

    case SOMEWHAT_TIMELY = "somewhat_timely";

    case NEVER_TIMELY = "not_very_timely";

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
