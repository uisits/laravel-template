<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question9Options: string implements HasLabel
{
    case VERY_FREQUENTLY = 'very_frequently';

    case FREQUENTLY = 'frequently';

    case SOMETIMES = 'sometimes';

    case INFREQUENTLY = 'infrequently';

    case NOT_AT_ALL = 'not_at_all';

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
