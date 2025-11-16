<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const string ADMIN_USER = 'admin-user';
    public const string LAWYER_1 = 'lawyer-1';
    public const string LAWYER_2 = 'lawyer-2';
    public const string LAWYER_3 = 'lawyer-3';
    public const string SECRETARY_1 = 'secretary-1';
    public const string SECRETARY_2 = 'secretary-2';

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin User
        $admin = new User();
        $admin->setEmail('admin@sebego.co.bw');
        $admin->setFirstName('Thabo');
        $admin->setLastName('Modise');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setIsActive(true);
        $manager->persist($admin);
        $this->addReference(self::ADMIN_USER, $admin);

        // Senior Lawyer 1
        $lawyer1 = new User();
        $lawyer1->setEmail('neo.kgosi@sebego.co.bw');
        $lawyer1->setFirstName('Neo');
        $lawyer1->setLastName('Kgosi');
        $lawyer1->setRoles(['ROLE_LAWYER']);
        $lawyer1->setPassword($this->passwordHasher->hashPassword($lawyer1, 'password123'));
        $lawyer1->setIsActive(true);
        $manager->persist($lawyer1);
        $this->addReference(self::LAWYER_1, $lawyer1);

        // Lawyer 2
        $lawyer2 = new User();
        $lawyer2->setEmail('mpho.setlhare@sebego.co.bw');
        $lawyer2->setFirstName('Mpho');
        $lawyer2->setLastName('Setlhare');
        $lawyer2->setRoles(['ROLE_LAWYER']);
        $lawyer2->setPassword($this->passwordHasher->hashPassword($lawyer2, 'password123'));
        $lawyer2->setIsActive(true);
        $manager->persist($lawyer2);
        $this->addReference(self::LAWYER_2, $lawyer2);

        // Junior Lawyer 3
        $lawyer3 = new User();
        $lawyer3->setEmail('kago.motswana@sebego.co.bw');
        $lawyer3->setFirstName('Kago');
        $lawyer3->setLastName('Motswana');
        $lawyer3->setRoles(['ROLE_LAWYER']);
        $lawyer3->setPassword($this->passwordHasher->hashPassword($lawyer3, 'password123'));
        $lawyer3->setIsActive(true);
        $manager->persist($lawyer3);
        $this->addReference(self::LAWYER_3, $lawyer3);

        // Secretary 1
        $secretary1 = new User();
        $secretary1->setEmail('keabetswe.mokone@sebego.co.bw');
        $secretary1->setFirstName('Keabetswe');
        $secretary1->setLastName('Mokone');
        $secretary1->setRoles(['ROLE_SECRETARY']);
        $secretary1->setPassword($this->passwordHasher->hashPassword($secretary1, 'password123'));
        $secretary1->setIsActive(true);
        $manager->persist($secretary1);
        $this->addReference(self::SECRETARY_1, $secretary1);

        // Secretary 2
        $secretary2 = new User();
        $secretary2->setEmail('boitumelo.phiri@sebego.co.bw');
        $secretary2->setFirstName('Boitumelo');
        $secretary2->setLastName('Phiri');
        $secretary2->setRoles(['ROLE_SECRETARY']);
        $secretary2->setPassword($this->passwordHasher->hashPassword($secretary2, 'password123'));
        $secretary2->setIsActive(true);
        $manager->persist($secretary2);
        $this->addReference(self::SECRETARY_2, $secretary2);

        $manager->flush();
    }
}
