<?php

namespace App\DataFixtures;

use App\Entity\Matter;
use App\Entity\MatterUpdate;
use App\Entity\User;
use App\Enum\UpdateType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MatterUpdateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Updates for Matter 1 - Civil Case
        $update1 = new MatterUpdate();
        $update1->setMatter($this->getReference(MatterFixtures::MATTER_1, Matter::class));
        $update1->setUpdateType(UpdateType::UPDATE_TYPE_INTERNAL_NOTE);
        $update1->setContent('Initial consultation held with client Lesego Molapisi. Client provided documentation showing contract breach by Motlhala Construction. Builder failed to complete work as per agreed specifications and timeline. Missing roof trusses and incomplete plumbing work. Client seeks damages of P250,000 plus legal costs.');
        $update1->setEventDate(new \DateTimeImmutable('2024-03-15'));
        $update1->setCreatedBy($this->getReference(UserFixtures::LAWYER_1, User::class)->getUserIdentifier());
        $manager->persist($update1);

        $update2 = new MatterUpdate();
        $update2->setMatter($this->getReference(MatterFixtures::MATTER_1, Matter::class));
        $update2->setUpdateType(UpdateType::UPDATE_TYPE_COURT_FILING);
        $update2->setContent('Statement of claim filed at High Court Registry on 22 March 2024. Case number allocated: GBR/CIV/2024/001. Summons issued for service on defendant.');
        $update2->setEventDate(new \DateTimeImmutable('2024-03-22'));
        $update2->setCreatedBy($this->getReference(UserFixtures::SECRETARY_1, User::class)->getUserIdentifier());
        $manager->persist($update2);

        $update3 = new MatterUpdate();
        $update3->setMatter($this->getReference(MatterFixtures::MATTER_1, Matter::class));
        $update3->setUpdateType(UpdateType::UPDATE_TYPE_NEXT_STEP);
        $update3->setContent('Defendant filed appearance to defend on 5 April 2024. Discovery process commencing. Awaiting defendant\'s plea and discovery of documents. Scheduled case management conference for 15 May 2024.');
        $update3->setEventDate(new \DateTimeImmutable('2024-04-05'));
        $update3->setCreatedBy($this->getReference(UserFixtures::LAWYER_1, User::class)->getUserIdentifier());
        $manager->persist($update3);

        // Updates for Matter 2 - Labour Dispute
        $update4 = new MatterUpdate();
        $update4->setMatter($this->getReference(MatterFixtures::MATTER_2, Matter::class));
        $update4->setUpdateType(UpdateType::UPDATE_TYPE_INTERNAL_NOTE);
        $update4->setContent('Client Tshepiso Khumo dismissed without notice after 5 years employment. No disciplinary hearing conducted. Client denied opportunity to state case. Kgalagadi Trading claims gross misconduct but no supporting evidence provided. Strong case for unfair dismissal.');
        $update4->setEventDate(new \DateTimeImmutable('2024-04-20'));
        $update4->setCreatedBy($this->getReference(UserFixtures::LAWYER_2, User::class)->getUserIdentifier());
        $manager->persist($update4);

        $update5 = new MatterUpdate();
        $update5->setMatter($this->getReference(MatterFixtures::MATTER_2, Matter::class));
        $update5->setUpdateType(UpdateType::UPDATE_TYPE_CLIENT_MEETING);
        $update5->setContent('Meeting with client to review evidence. Client provided employment contract, payslips, and termination letter. Discussed calculation of damages: notice pay, severance, and damages for unfair dismissal. Client agrees to pursue matter through Labour Commissioner initially.');
        $update5->setEventDate(new \DateTimeImmutable('2024-04-25'));
        $update5->setCreatedBy($this->getReference(UserFixtures::LAWYER_2, User::class)->getUserIdentifier());
        $manager->persist($update5);

        $update6 = new MatterUpdate();
        $update6->setMatter($this->getReference(MatterFixtures::MATTER_2, Matter::class));
        $update6->setUpdateType(UpdateType::UPDATE_TYPE_NEXT_STEP);
        $update6->setContent('Referral filed with Office of Labour Commissioner on 2 May 2024. Conciliation hearing scheduled for 20 June 2024. Preparing client for conciliation process. If conciliation fails, will proceed to Industrial Court.');
        $update6->setEventDate(new \DateTimeImmutable('2024-05-02'));
        $update6->setCreatedBy($this->getReference(UserFixtures::LAWYER_2, User::class)->getUserIdentifier());
        $manager->persist($update6);

        // Updates for Matter 3 - Conveyancing
        $update7 = new MatterUpdate();
        $update7->setMatter($this->getReference(MatterFixtures::MATTER_3, Matter::class));
        $update7->setUpdateType(UpdateType::UPDATE_TYPE_INTERNAL_NOTE);
        $update7->setContent('Sale of Plot 567, Phakalane for P1,850,000. Buyer Sarah Ndlovu approved for mortgage finance by First National Bank. Seller Onalenna Segwai has clear title. Property search completed - no encumbrances or restrictions on title. Transfer can proceed.');
        $update7->setEventDate(new \DateTimeImmutable('2024-05-10'));
        $update7->setCreatedBy($this->getReference(UserFixtures::LAWYER_1, User::class)->getUserIdentifier());
        $manager->persist($update7);

        $update8 = new MatterUpdate();
        $update8->setMatter($this->getReference(MatterFixtures::MATTER_3, Matter::class));
        $update8->setUpdateType(UpdateType::UPDATE_TYPE_NEXT_STEP);
        $update8->setContent('Transfer documents prepared and signed by both parties. Deed of sale executed. Transfer duty paid (P37,000). Documents lodged with Deeds Registry on 3 June 2024. Awaiting registration - expected 4-6 weeks. Occupational certificate obtained from Gaborone City Council.');
        $update8->setEventDate(new \DateTimeImmutable('2024-06-03'));
        $update8->setCreatedBy($this->getReference(UserFixtures::SECRETARY_1, User::class)->getUserIdentifier());
        $manager->persist($update8);

        // Updates for Matter 4 - Divorce (Closed)
        $update9 = new MatterUpdate();
        $update9->setMatter($this->getReference(MatterFixtures::MATTER_4, Matter::class));
        $update9->setUpdateType(UpdateType::UPDATE_TYPE_SETTLEMENT);
        $update9->setContent('Parties reached agreement on division of matrimonial property. House to be sold and proceeds divided 60/40 in favour of petitioner (client). Client retains Toyota Hilux, respondent retains Honda Fit. Pension benefits to be divided as per Pension Act. No minor children involved.');
        $update9->setEventDate(new \DateTimeImmutable('2024-02-14'));
        $update9->setCreatedBy($this->getReference(UserFixtures::LAWYER_2, User::class)->getUserIdentifier());
        $manager->persist($update9);

        $update10 = new MatterUpdate();
        $update10->setMatter($this->getReference(MatterFixtures::MATTER_4, Matter::class));
        $update10->setUpdateType(UpdateType::UPDATE_TYPE_JUDGMENT);
        $update10->setContent('Consent order granted by Family Court on 20 March 2024. Decree absolute issued. Marriage dissolved. Property division order as per settlement agreement. No costs order. File closed.');
        $update10->setEventDate(new \DateTimeImmutable('2024-03-20'));
        $update10->setCreatedBy($this->getReference(UserFixtures::LAWYER_2, User::class)->getUserIdentifier());
        $manager->persist($update10);

        // Updates for Matter 5 - Corporate
        $update11 = new MatterUpdate();
        $update11->setMatter($this->getReference(MatterFixtures::MATTER_5, Matter::class));
        $update11->setUpdateType(UpdateType::UPDATE_TYPE_INTERNAL_NOTE);
        $update11->setContent('Youth Empowerment Trust selected by Ministry of Youth to provide skills training program. Initial contract value P2.5 million over 3 years. Ministry draft contract reviewed - several problematic clauses identified regarding payment terms, liability, and termination provisions. Prepared memo to client with recommended amendments.');
        $update11->setEventDate(new \DateTimeImmutable('2024-06-05'));
        $update11->setCreatedBy($this->getReference(UserFixtures::LAWYER_1, User::class)->getUserIdentifier());
        $manager->persist($update11);

        $update12 = new MatterUpdate();
        $update12->setMatter($this->getReference(MatterFixtures::MATTER_5, Matter::class));
        $update12->setUpdateType(UpdateType::UPDATE_TYPE_CLIENT_MEETING);
        $update12->setContent('Meeting with Trust board members to discuss contract amendments. Board approved recommended changes. Particular concern regarding unlimited liability clause and 30-day payment terms. Instructed to negotiate these provisions with Ministry legal team. Supporting lawyer Mpho Setlhare to attend negotiation meeting.');
        $update12->setEventDate(new \DateTimeImmutable('2024-06-12'));
        $update12->setCreatedBy($this->getReference(UserFixtures::LAWYER_1, User::class)->getUserIdentifier());
        $manager->persist($update12);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            MatterFixtures::class,
        ];
    }
}
