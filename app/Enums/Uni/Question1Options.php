<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question1Options: string implements HasLabel
{

    case FRESHMAN = 'freshman';

    case SOPHOMORE = 'sophomore';

    case JUNIOR = 'junior';

    case SENIOR = 'senior';

    case NOT_SURE = 'not_sure';

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
