<?php

namespace App\enums;

enum MembershipDuration: int
{
    case FREE = 0;
    case ONE_MONTH = 1;
    case THREE_MONTHS = 3;
    case SIX_MONTHS = 6;
    case TWELVE_MONTHS = 12;
}


