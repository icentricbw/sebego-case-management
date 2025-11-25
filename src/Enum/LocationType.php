<?php

namespace App\Enum;

enum LocationType: string
{
    case FILING_ROOM = 'FILING_ROOM';
    case OFFICE = 'OFFICE';
    case DESK = 'DESK';
    case STORAGE = 'STORAGE';
    case ARCHIVE = 'ARCHIVE';
    case COURT = 'COURT';
    case CLIENT = 'CLIENT';
    case EXTERNAL = 'EXTERNAL';
    case OTHER = 'OTHER';

    public function getLabel(): string
    {
        return match($this) {
            self::FILING_ROOM => 'Filing Room',
            self::OFFICE => 'Office',
            self::DESK => 'Desk',
            self::STORAGE => 'Storage Room',
            self::ARCHIVE => 'Archive',
            self::COURT => 'Court',
            self::CLIENT => 'With Client',
            self::EXTERNAL => 'External Location',
            self::OTHER => 'Other',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::FILING_ROOM => 'fas fa-folder-open',
            self::OFFICE => 'fas fa-building',
            self::DESK => 'fas fa-desk',
            self::STORAGE => 'fas fa-warehouse',
            self::ARCHIVE => 'fas fa-archive',
            self::COURT => 'fas fa-gavel',
            self::CLIENT => 'fas fa-user',
            self::EXTERNAL => 'fas fa-map-marker-alt',
            self::OTHER => 'fas fa-map-pin',
        };
    }

    public function getBadgeColor(): string
    {
        return match($this) {
            self::FILING_ROOM => 'primary',
            self::OFFICE => 'info',
            self::DESK => 'secondary',
            self::STORAGE => 'warning',
            self::ARCHIVE => 'dark',
            self::COURT => 'danger',
            self::CLIENT => 'success',
            self::EXTERNAL => 'info',
            self::OTHER => 'secondary',
        };
    }
}
