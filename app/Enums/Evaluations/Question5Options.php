<?php

namespace App\Enums\Evaluations;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question5Options: string implements HasLabel
{

    case REQUIRED_FOR_MAJOR = 'required_for_my_major';

    case REQUIRED_FOR_GENED_ECCE_CAPSCHO_HON = 'required_for_general_education/ecce/capital_scholars_honors';

    case ELECTIVE = 'an_elective';

    case MINOR = 'counts_toward_my_minor';

    case CERTIFICATE = 'counts_toward_a_certificate';

    case PREFER_NOT_TO_ANSWER = 'prefer_not_to_answer';

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return Str::of($this->value)
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
