<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question7Options: string implements HasLabel
{
    case VERY_EFFECTIVELY = 'very_effectively';

    case MODERATELY__EFFECTIVELY = 'moderately_effectively';

    case SLIGHTLY_EFFECTIVELY = 'slightly_effectively';

    case NOT_VERY_EFFECTIVELY = 'not_very_effectively';

    case NO_FEEDBACK = 'i_never_saw_a_need_for_the_course_facilitator_to_intervene_in_discussions';

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
