<?php

namespace App\enums;

enum NotificationType: string
{
    case SMS = 'SMS';
    case EMAIL = 'EMAIL';
    case PUSH = 'PUSH';

}