<?php

namespace App\Enums\Evaluations;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Question4Options: string implements HasLabel
{

    case A = 'A';

    case A_MINUS = 'A-';

    case B_PLUS = 'B+';

    case B = 'B';

    case B_MINUS = 'B-';

    case C_PLUS = 'C+';

    case C = 'C';

    case C_MINUS = 'C-';

    case D_PLUS = 'D+';

    case D = 'D';

    case D_MINUS = 'D-';

    case F = 'F';

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->value;
    }
}
