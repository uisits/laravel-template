<?php

namespace App\Enums;

enum EvaluationType: string
{
    case LECTURE = "lecture";

    case ONLINE = "online";

    case LAB = "lab";

    case CONFERENCE = "conference";

    case UNKNOWN = "unknown";
}
