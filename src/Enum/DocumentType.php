<?php

namespace App\Enum;

enum DocumentType: string
{
    case CONTRACT = 'CONTRACT';
    case INVOICE = 'INVOICE';
    case RECEIPT = 'RECEIPT';
    case LETTER = 'LETTER';
    case COURT_DOCUMENT = 'COURT_DOCUMENT';
    case EVIDENCE = 'EVIDENCE';
    case AFFIDAVIT = 'AFFIDAVIT';
    case PLEADING = 'PLEADING';
    case MOTION = 'MOTION';
    case AGREEMENT = 'AGREEMENT';
    case DEED = 'DEED';
    case CERTIFICATE = 'CERTIFICATE';
    case OTHER = 'OTHER';
    case PASSPORT = 'PASSPORT';
    case ID = 'ID';

    /**
     * Get human-readable label
     */
    public function getLabel(): string
    {
        return match($this) {
            self::CONTRACT => 'Contract',
            self::INVOICE => 'Invoice',
            self::RECEIPT => 'Receipt',
            self::LETTER => 'Letter',
            self::COURT_DOCUMENT => 'Court Document',
            self::EVIDENCE => 'Evidence',
            self::AFFIDAVIT => 'Affidavit',
            self::PLEADING => 'Pleading',
            self::MOTION => 'Motion',
            self::AGREEMENT => 'Agreement',
            self::DEED => 'Deed',
            self::CERTIFICATE => 'Certificate',
            self::OTHER => 'Other',
            self::PASSPORT => 'Passport',
            self::ID => 'National ID'
        };
    }

    /**
     * Get badge color for display
     */
    public function getBadgeColor(): string
    {
        return match($this) {
            self::CONTRACT, self::AGREEMENT => 'primary',
            self::INVOICE => 'success',
            self::RECEIPT => 'info',
            self::LETTER => 'secondary',
            self::COURT_DOCUMENT, self::PLEADING, self::MOTION => 'warning',
            self::EVIDENCE, self::AFFIDAVIT => 'danger',
            self::DEED, self::CERTIFICATE => 'dark',
            self::OTHER => 'secondary',
        };
    }
}
