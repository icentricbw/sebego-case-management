<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Enum\ClientType;
use App\Enum\IdentificationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public const string CLIENT_INDIVIDUAL_1 = 'client-individual-1';
    public const string CLIENT_INDIVIDUAL_2 = 'client-individual-2';
    public const string CLIENT_INDIVIDUAL_3 = 'client-individual-3';
    public const string CLIENT_INDIVIDUAL_4 = 'client-individual-4';
    public const string CLIENT_ORG_1 = 'client-org-1';
    public const string CLIENT_ORG_2 = 'client-org-2';
    public const string CLIENT_ORG_3 = 'client-org-3';

    public function load(ObjectManager $manager): void
    {
        // Individual Client 1
        $client1 = new Client();
        $client1->setClientType(ClientType::INDIVIDUAL);
        $client1->setFullName('Lesego Molapisi');
        $client1->setIdentificationType(IdentificationType::OMANG);
        $client1->setIdentificationNumber('419823456');
        $client1->setPrimaryPhone('72123456');
        $client1->setEmail('lesego.molapisi@gmail.com');
        $client1->setResidentialAddress('Plot 2456, Extension 12, Gaborone');
        $client1->setPostalAddress('P.O. Box 1234, Gaborone');
        $manager->persist($client1);
        $this->addReference(self::CLIENT_INDIVIDUAL_1, $client1);

        // Individual Client 2
        $client2 = new Client();
        $client2->setClientType(ClientType::INDIVIDUAL);
        $client2->setFullName('Tshepiso Khumo');
        $client2->setIdentificationType(IdentificationType::OMANG);
        $client2->setIdentificationNumber('520934567');
        $client2->setPrimaryPhone('73234567');
        $client2->setSecondaryPhone('3901234');
        $client2->setEmail('tshepiso.khumo@yahoo.com');
        $client2->setResidentialAddress('Block 8, Plot 1234, Broadhurst, Gaborone');
        $client2->setPostalAddress('P.O. Box 5678, Gaborone');
        $manager->persist($client2);
        $this->addReference(self::CLIENT_INDIVIDUAL_2, $client2);

        // Individual Client 3 (with passport)
        $client3 = new Client();
        $client3->setClientType(ClientType::INDIVIDUAL);
        $client3->setFullName('Sarah Ndlovu');
        $client3->setIdentificationType(IdentificationType::PASSPORT);
        $client3->setIdentificationNumber('B1234567');
        $client3->setPrimaryPhone('74345678');
        $client3->setEmail('sarah.ndlovu@outlook.com');
        $client3->setResidentialAddress('Plot 567, Phakalane, Gaborone');
        $client3->setPostalAddress('P.O. Box 9012, Gaborone');
        $manager->persist($client3);
        $this->addReference(self::CLIENT_INDIVIDUAL_3, $client3);

        // Individual Client 4
        $client4 = new Client();
        $client4->setClientType(ClientType::INDIVIDUAL);
        $client4->setFullName('Onalenna Segwai');
        $client4->setIdentificationType(IdentificationType::OMANG);
        $client4->setIdentificationNumber('389012345');
        $client4->setPrimaryPhone('75456789');
        $client4->setEmail('ona.segwai@gmail.com');
        $client4->setResidentialAddress('Plot 3456, Old Naledi, Gaborone');
        $client4->setPostalAddress('Private Bag 234, Gaborone');
        $manager->persist($client4);
        $this->addReference(self::CLIENT_INDIVIDUAL_4, $client4);

        // Organization Client 1 - Construction Company
        $org1 = new Client();
        $org1->setClientType(ClientType::ORGANIZATION);
        $org1->setCompanyName('Motlhala Construction (Pty) Ltd');
        $org1->setRegistrationNumber('BW00123456');
        $org1->setAuthorizedRepresentativeName('Kagiso Motlhala');
        $org1->setAuthorizedRepresentativePhone('72567890');
        $org1->setAuthorizedRepresentativeEmail('kagiso@motlhalaconstruction.co.bw');
        $org1->setPrimaryPhone('3900123');
        $org1->setEmail('info@motlhalaconstruction.co.bw');
        $org1->setPhysicalAddress('Plot 123, Industrial Site, Gaborone');
        $org1->setPostalAddress('P.O. Box 4567, Gaborone');
        $manager->persist($org1);
        $this->addReference(self::CLIENT_ORG_1, $org1);

        // Organization Client 2 - Retail Business
        $org2 = new Client();
        $org2->setClientType(ClientType::ORGANIZATION);
        $org2->setCompanyName('Kgalagadi Trading (Pty) Ltd');
        $org2->setRegistrationNumber('BW00234567');
        $org2->setAuthorizedRepresentativeName('Boipelo Seretse');
        $org2->setAuthorizedRepresentativePhone('73678901');
        $org2->setAuthorizedRepresentativeEmail('boipelo@kgalagaditrading.co.bw');
        $org2->setPrimaryPhone('3901234');
        $org2->setEmail('admin@kgalagaditrading.co.bw');
        $org2->setPhysicalAddress('Plot 5678, Main Mall, Gaborone');
        $org2->setPostalAddress('P.O. Box 7890, Gaborone');
        $manager->persist($org2);
        $this->addReference(self::CLIENT_ORG_2, $org2);

        // Organization Client 3 - NGO
        $org3 = new Client();
        $org3->setClientType(ClientType::ORGANIZATION);
        $org3->setCompanyName('Botswana Youth Empowerment Trust');
        $org3->setRegistrationNumber('NGO/2018/123');
        $org3->setAuthorizedRepresentativeName('Phenyo Marumo');
        $org3->setAuthorizedRepresentativePhone('74789012');
        $org3->setAuthorizedRepresentativeEmail('phenyo@byetrust.org.bw');
        $org3->setPrimaryPhone('3902345');
        $org3->setEmail('contact@byetrust.org.bw');
        $org3->setPhysicalAddress('Plot 2345, Extension 15, Gaborone');
        $org3->setPostalAddress('P.O. Box 3456, Gaborone');
        $manager->persist($org3);
        $this->addReference(self::CLIENT_ORG_3, $org3);

        $manager->flush();
    }
}
