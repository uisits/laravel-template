<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question6Options: string implements HasLabel
{
    case VERY_HELPFUL = 'very_helpful';

    case MODERATELY__HELPFUL = 'moderately_helpful';

    case NOT_VERY_HELPFUL = 'not_very_helpful';

    case NOT_ANSWERED = 'my_questions_were_not_answered';

    case NOT_APPLICABLE = 'not_applicable/i_did_not_ask_questions';

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
