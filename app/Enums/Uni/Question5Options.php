<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question5Options:string implements HasLabel
{
    case VERY_HELPFUL = 'very_helpful';

    case MODERATELY__HELPFUL = 'moderately_helpful';

    case SLIGHTLY_HELPFUL = 'slightly_helpful';

    case NOT_VERY_HELPFUL = 'not_very_helpful';

    case NO_FEEDBACK = 'there_was_no_feedback_given/It_was_not_needed';

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
