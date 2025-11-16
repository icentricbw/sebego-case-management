<?php

namespace App\DataFixtures;

use App\Entity\CaseType;
use App\Entity\Client;
use App\Entity\Matter;
use App\Entity\MatterClient;
use App\Entity\MatterLawyer;
use App\Entity\User;
use App\Enum\ClientRole;
use App\Enum\RoleType;
use App\Enum\StatusType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MatterFixtures extends Fixture implements DependentFixtureInterface
{
    public const  MATTER_1 = 'matter-1';
    public const  MATTER_2 = 'matter-2';
    public const  MATTER_3 = 'matter-3';
    public const  MATTER_4 = 'matter-4';
    public const  MATTER_5 = 'matter-5';


    public function load(ObjectManager $manager): void
    {

        // Matter 1 - Civil Claim (Active)
        $matter1 = new Matter();
        $matter1->setFileNumber(1);
        $matter1->setDescription('Breach of contract claim - Construction dispute regarding incomplete building work at Plot 2456, Extension 12');
        $matter1->setNotes('Met with client at office. Client explained that Defendant failed to complete construction work on Plot 2456, Extension 12. Works stopped at 60% completion. Client has paid BWP 450,000 of BWP 750,000 contract price. Remaining defects include incomplete roofing, unfinished electrical work, and missing plumbing fixtures. Client provided construction contract, proof of payments, and photographs of incomplete work.');
        $matter1->setCaseType( $this->getReference(CaseTypeFixtures::CASE_TYPE_LITIGATION, CaseType::class));
        $matter1->setStatusType(StatusType::ACTIVE);
        $matter1->setFilingDate(new \DateTimeImmutable('2024-03-15'));
        $matter1->setLeadLawyer($this->getReference(UserFixtures::LAWYER_1, User::class));
        $matter1->setSecretary($this->getReference(UserFixtures::SECRETARY_1, User::class));
        $manager->persist($matter1);
        $this->addReference(self::MATTER_1, $matter1);

        // Link clients to matter 1
        $mc1 = new MatterClient();
        $mc1->setMatter($matter1);
        $mc1->setClient($this->getReference(ClientFixtures::CLIENT_INDIVIDUAL_1, Client::class));
        $mc1->setClientRole(ClientRole::CLIENT_ROLE_PLAINTIFF);
        $manager->persist($mc1);

        $mc2 = new MatterClient();
        $mc2->setMatter($matter1);
        $mc2->setClient($this->getReference(ClientFixtures::CLIENT_ORG_1, Client::class));
        $mc2->setClientRole(ClientRole::CLIENT_ROLE_DEFENDANT);
        $manager->persist($mc2);

        // Assign supporting lawyer
        $ml1 = new MatterLawyer();
        $ml1->setMatter($matter1);
        $ml1->setLawyer($this->getReference(UserFixtures::LAWYER_3, User::class));
        $ml1->setRoleType(RoleType::SUPPORTING);
        $manager->persist($ml1);

        // Matter 2 - Labour Dispute (Active)
        $matter2 = new Matter();
        $matter2->setFileNumber(2);
        $matter2->setDescription('Unfair dismissal claim - Employee terminated without proper notice or severance');
        $matter2->setNotes('Met with client at office. Client explained that Defendant failed to complete construction work on Plot 2456, Extension 12. Works stopped at 60% completion. Client has paid BWP 450,000 of BWP 750,000 contract price. Remaining defects include incomplete roofing, unfinished electrical work, and missing plumbing fixtures. Client provided construction contract, proof of payments, and photographs of incomplete work.');
        $matter2->setCaseType( $this->getReference(CaseTypeFixtures::CASE_TYPE_LITIGATION, CaseType::class));
        $matter2->setStatusType(StatusType::ACTIVE);
        $matter2->setFilingDate(new \DateTimeImmutable('2024-04-20'));
        $matter2->setLeadLawyer($this->getReference(UserFixtures::LAWYER_2, User::class));
        $matter2->setSecretary($this->getReference(UserFixtures::SECRETARY_2, User::class));
        $manager->persist($matter2);
        $this->addReference(self::MATTER_2, $matter2);

        $mc3 = new MatterClient();
        $mc3->setMatter($matter2);
        $mc3->setClient($this->getReference(ClientFixtures::CLIENT_INDIVIDUAL_2, Client::class));
        $mc3->setClientRole(ClientRole::CLIENT_ROLE_APPLICANT);
        $manager->persist($mc3);

        $mc4 = new MatterClient();
        $mc4->setMatter($matter2);
        $mc4->setClient($this->getReference(ClientFixtures::CLIENT_ORG_2, Client::class));
        $mc4->setClientRole(ClientRole::CLIENT_ROLE_RESPONDENT);
        $manager->persist($mc4);

        // Matter 3 - Conveyancing (Active)
        $matter3 = new Matter();
        $matter3->setFileNumber(3);
        $matter3->setDescription('Property transfer - Sale of Plot 567, Phakalane, Gaborone from seller to buyer');
        $matter3->setNotes('Particulars of Claim filed at High Court. Case number assigned: MAHGB-001-24. Defendant has 21 days to file appearance and defence. Court filing fees paid (BWP 2,500). Original sealed documents collected and stored in matter file.');
        $matter3->setCaseType($this->getReference(CaseTypeFixtures::CASE_TYPE_CONVEYANCING, CaseType::class));
        $matter3->setStatusType(StatusType::ACTIVE);
        $matter3->setFilingDate(new \DateTimeImmutable('2024-05-10'));
        $matter3->setLeadLawyer($this->getReference(UserFixtures::LAWYER_1, User::class));
        $matter3->setSecretary($this->getReference(UserFixtures::SECRETARY_1, User::class));
        $manager->persist($matter3);
        $this->addReference(self::MATTER_3, $matter3);

        $mc5 = new MatterClient();
        $mc5->setMatter($matter3);
        $mc5->setClient($this->getReference(ClientFixtures::CLIENT_INDIVIDUAL_3, Client::class));
        $mc5->setClientRole(ClientRole::CLIENT_ROLE_BUYER);
        $manager->persist($mc5);

        $mc6 = new MatterClient();
        $mc6->setMatter($matter3);
        $mc6->setClient($this->getReference(ClientFixtures::CLIENT_INDIVIDUAL_4, Client::class));
        $mc6->setClientRole(ClientRole::CLIENT_ROLE_SELLER);
        $manager->persist($mc6);

        // Matter 4 - Family Law (Closed)
        $matter4 = new Matter();
        $matter4->setFileNumber(4);
        $matter4->setDescription('Divorce proceedings - Dissolution of marriage and division of matrimonial property');
        $matter4->setCaseType($this->getReference(CaseTypeFixtures::CASE_TYPE_LITIGATION, CaseType::class));
        $matter4->setStatusType(StatusType::CLOSED);
        $matter4->setFilingDate(new \DateTimeImmutable('2023-09-05'));
        $matter4->setClosingDate(new \DateTimeImmutable('2024-03-20'));
        $matter4->setLeadLawyer($this->getReference(UserFixtures::LAWYER_2, User::class));
        $matter4->setSecretary($this->getReference(UserFixtures::SECRETARY_2, User::class));
        $manager->persist($matter4);
        $this->addReference(self::MATTER_4, $matter4);

        $mc7 = new MatterClient();
        $mc7->setMatter($matter4);
        $mc7->setClient($this->getReference(ClientFixtures::CLIENT_INDIVIDUAL_2, Client::class));
        $mc7->setClientRole(ClientRole::CLIENT_ROLE_PETITIONER);
        $manager->persist($mc7);

        // Matter 5 - Corporate/Commercial (Active)
        $matter5 = new Matter();
        $matter5->setFileNumber(5);
        $matter5->setDescription('Contract review and negotiation for service agreement with government ministry');
        $matter5->setCaseType($this->getReference(CaseTypeFixtures::CASE_TYPE_LITIGATION, CaseType::class));
        $matter5->setStatusType(StatusType::ACTIVE);
        $matter5->setFilingDate(new \DateTimeImmutable('2024-06-01'));
        $matter5->setLeadLawyer($this->getReference(UserFixtures::LAWYER_1, User::class));
        $matter5->setSecretary($this->getReference(UserFixtures::SECRETARY_1, User::class));
        $manager->persist($matter5);
        $this->addReference(self::MATTER_5, $matter5);

        $mc8 = new MatterClient();
        $mc8->setMatter($matter5);
        $mc8->setClient($this->getReference(ClientFixtures::CLIENT_ORG_3, Client::class));
        $mc8->setClientRole(ClientRole::CLIENT_ROLE_CLAIMANT);
        $manager->persist($mc8);

        // Add supporting lawyer to matter 5
        $ml2 = new MatterLawyer();
        $ml2->setMatter($matter5);
        $ml2->setLawyer($this->getReference(UserFixtures::LAWYER_2,User::class));
        $ml2->setRoleType(RoleType::SUPPORTING);
        $manager->persist($ml2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ClientFixtures::class,
        ];
    }
}
