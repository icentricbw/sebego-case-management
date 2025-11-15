<?php

namespace App\Enum;

enum Priority: string
{
    case PRIORITY_LOW = 'LOW';
    case PRIORITY_MEDIUM = 'MEDIUM';
    case PRIORITY_HIGH = 'HIGH';
    case PRIORITY_URGENT = 'URGENT';
}
