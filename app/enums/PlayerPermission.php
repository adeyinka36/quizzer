<?php

namespace App\enums;

enum PlayerPermission : string
{
    case CAN_CONTROL_OWN_RESOURCES = 'ability:can-control-own-resources';
    case CAN_VIEW_QUESTIONS = 'ability:can-view-questions';
    case CAN_CREATE_GAME = 'ability:can-create-game';
    case CAN_VIEW_GAME = 'ability:can-view-game';
}

