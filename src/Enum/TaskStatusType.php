<?php

namespace App\Enum;

enum TaskStatusType: string
{
    case TASK_STATUS_PENDING = 'PENDING';
    case TASK_STATUS_IN_PROGRESS = 'IN_PROGRESS';
    case TASK_STATUS_COMPLETED = 'COMPLETED';
}
