<?php

namespace App\Enums\Uni;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question12Options: string implements HasLabel
{
    case GREAT_DEAL = 'a_great_deal';

    case TO_SOME_EXTENT = 'to_some_extent';

    case A_LITTLE_BIT = 'a_little_bit';

    case NOT_SURE = 'not_sure';

    case NOT_AT_ALL = 'not_at_all';


    public function getLabel(): ?string
    {
        return Str::of($this->value)
            ->headline()
            ->value();
    }
}
