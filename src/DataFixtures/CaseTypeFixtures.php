<?php

namespace App\DataFixtures;

use App\Entity\CaseType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CaseTypeFixtures extends Fixture
{
    public const string CASE_TYPE_LITIGATION = 'case_type_litigation';
    public const string CASE_TYPE_CONVEYANCING = 'case_type_conveyancing';

    public function load(ObjectManager $manager): void
    {
        $caseTypes = [
            self::CASE_TYPE_LITIGATION => 'Litigation',
            self::CASE_TYPE_CONVEYANCING => 'Conveyancing',
        ];

        foreach ($caseTypes as $reference => $name) {
            $caseType = new CaseType();
            $caseType->setName($name);

            $manager->persist($caseType);
            $this->addReference($reference, $caseType);
        }

        $manager->flush();
    }
}
