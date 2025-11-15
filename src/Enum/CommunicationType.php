<?php

namespace App\Enum;

enum CommunicationType: string
{
    case COMM_TYPE_MEETING = 'MEETING';
    case COMM_TYPE_PHONE_CALL = 'PHONE_CALL';
    case COMM_TYPE_EMAIL = 'EMAIL';
    case COMM_TYPE_SMS = 'SMS';
    case COMM_TYPE_VIDEO_CALL = 'VIDEO_CALL';
    case COMM_TYPE_LETTER = 'LETTER';
}
