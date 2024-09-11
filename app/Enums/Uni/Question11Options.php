<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question11Options: string implements HasLabel
{
    case GREAT_DEAL = 'a_great_deal';

    case TO_SOME_EXTENT = 'to_some_extent';

    case A_LITTLE_BIT = 'a_little_bit';

    case NOT_SURE = 'not_sure';

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
