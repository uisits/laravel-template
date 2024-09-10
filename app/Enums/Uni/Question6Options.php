<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;

enum Question6Options: string
{
    case VERY_HELPFUL = 'very_helpful';

    case MODERATELY__HELPFUL = 'moderately_helpful';

    case NOT_VERY_HELPFUL = 'not_very_helpful';

    case NOT_ANSWERED = 'my_questions_were_not_answered';

    case NOT_APPLICABLE = 'not_applicable/i_did_not_ask_questions';
}
