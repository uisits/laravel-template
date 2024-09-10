<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;

enum Question4Options: string implements HasLabel
{
    case VERY_TIMELY = "very_timely";

    case MODERATELY_TIMELY = "moderately_timely";

    case SOMEWHAT_TIMELY = "somewhat_timely";

    case NEVER_TIMELY = "not_very_timely";

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
