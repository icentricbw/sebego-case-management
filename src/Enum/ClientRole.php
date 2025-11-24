<?php

namespace App\Enum;

enum ClientRole: string
{
    case PLAINTIFF = 'PLAINTIFF';
    case DEFENDANT = 'DEFENDANT';
    case APPLICANT = 'APPLICANT';
    case RESPONDENT = 'RESPONDENT';
    case PETITIONER = 'PETITIONER';
    case CLAIMANT = 'CLAIMANT';
    case BUYER = 'BUYER';
    case SELLER = 'SELLER';
    case LANDLORD = 'LANDLORD';
    case TENANT = 'TENANT';
    case COMPLAINANT = 'COMPLAINANT';
    case ACCUSED = 'ACCUSED';
    case APPELLANT = 'APPELLANT';
    case EXECUTOR = 'EXECUTOR';
    case BENEFICIARY = 'BENEFICIARY';
    case WITNESS = 'WITNESS';
    case THIRD_PARTY = 'THIRD_PARTY';
    case OTHER = 'OTHER';

    public function getLabel(): string
    {
        return match($this) {
            self::PLAINTIFF => 'Plaintiff',
            self::DEFENDANT => 'Defendant',
            self::APPLICANT => 'Applicant',
            self::RESPONDENT => 'Respondent',
            self::PETITIONER => 'Petitioner',
            self::CLAIMANT => 'Claimant',
            self::BUYER => 'Buyer',
            self::SELLER => 'Seller',
            self::LANDLORD => 'Landlord',
            self::TENANT => 'Tenant',
            self::COMPLAINANT => 'Complainant',
            self::ACCUSED => 'Accused',
            self::APPELLANT => 'Appellant',
            self::EXECUTOR => 'Executor',
            self::BENEFICIARY => 'Beneficiary',
            self::WITNESS => 'Witness',
            self::THIRD_PARTY => 'Third Party',
            self::OTHER => 'Other',
        };
    }
}
