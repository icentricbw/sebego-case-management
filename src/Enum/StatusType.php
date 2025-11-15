<?php

namespace App\Enum;

enum StatusType: string
{
    case ACTIVE = 'ACTIVE';
    case CLOSED = 'CLOSED';
    case ARCHIVED = 'ARCHIVED';
}
