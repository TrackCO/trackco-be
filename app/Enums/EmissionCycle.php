<?php

namespace App\Enums;

enum EmissionCycle: int
{
    case WEEKLY     = 1;
    case MONTHLY    = 2;
    case ANNUALLY   = 3;
}
