<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;

enum Question5Options:string implements HasLabel
{
    case VERY_HELPFUL = 'very_helpful';

    case MODERATELY__HELPFUL = 'moderately_helpful';

    case SLIGHTLY_HELPFUL = 'slightly_helpful';

    case NOT_VERY_HELPFUL = 'not_very_helpful';

    case NO_FEEDBACK = 'there_was_no_feedback_given/It_was_not_needed';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
