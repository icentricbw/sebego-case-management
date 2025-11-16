<?php

namespace App\DataFixtures;

use App\Entity\Matter;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\Priority;
use App\Enum\TaskStatusType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Task 1 - Urgent, Pending
        $task1 = new Task();
        $task1->setTitle('File court papers for GBR/CIV/2024/001');
        $task1->setDescription('Prepare and file statement of claim at High Court registry before 5pm deadline');
        $task1->setMatter($this->getReference(MatterFixtures::MATTER_1,Matter::class));
        $task1->setAssignedTo($this->getReference(UserFixtures::LAWYER_1,User::class));
        $task1->setCreatedBy($this->getReference(UserFixtures::SECRETARY_1,User::class)->getUserIdentifier());
        $task1->setPriority(Priority::PRIORITY_URGENT);
        $task1->setTaskStatusType(TaskStatusType::TASK_STATUS_PENDING);
        $task1->setDueDate(new \DateTimeImmutable('tomorrow'));
        $manager->persist($task1);

        // Task 2 - High, In Progress
        $task2 = new Task();
        $task2->setTitle('Prepare witness statements');
        $task2->setDescription('Interview and prepare statements for three witnesses in construction dispute case');
        $task2->setMatter($this->getReference(MatterFixtures::MATTER_1,Matter::class));
        $task2->setAssignedTo($this->getReference(UserFixtures::LAWYER_3,User::class));
        $task2->setCreatedBy($this->getReference(UserFixtures::LAWYER_1,User::class)->getUserIdentifier());
        $task2->setPriority(Priority::PRIORITY_HIGH);
        $task2->setTaskStatusType(TaskStatusType::TASK_STATUS_IN_PROGRESS);
        $task2->setDueDate(new \DateTimeImmutable('+5 days'));
        $manager->persist($task2);

        // Task 3 - Medium, Pending
        $task3 = new Task();
        $task3->setTitle('Draft settlement proposal');
        $task3->setDescription('Prepare settlement proposal for unfair dismissal claim including compensation calculations');
        $task3->setMatter($this->getReference(MatterFixtures::MATTER_2,Matter::class));
        $task3->setAssignedTo($this->getReference(UserFixtures::LAWYER_2,User::class));
        $task3->setCreatedBy($this->getReference(UserFixtures::LAWYER_2,User::class)->getUserIdentifier());
        $task3->setPriority(Priority::PRIORITY_MEDIUM);
        $task3->setTaskStatusType(TaskStatusType::TASK_STATUS_PENDING);
        $task3->setDueDate(new \DateTimeImmutable('+7 days'));
        $manager->persist($task3);

        // Task 4 - High, Pending
        $task4 = new Task();
        $task4->setTitle('Request land certificate from Lands Department');
        $task4->setDescription('Submit application for certified copy of title deed for Plot 567, Phakalane');
        $task4->setMatter($this->getReference(MatterFixtures::MATTER_3,Matter::class));
        $task4->setAssignedTo($this->getReference(UserFixtures::SECRETARY_1,User::class));
        $task4->setCreatedBy($this->getReference(UserFixtures::LAWYER_1,User::class)->getUserIdentifier());
        $task4->setPriority(Priority::PRIORITY_HIGH);
        $task4->setTaskStatusType(TaskStatusType::TASK_STATUS_PENDING);
        $task4->setDueDate(new \DateTimeImmutable('+3 days'));
        $manager->persist($task4);

        // Task 5 - Low, Completed
        $task5 = new Task();
        $task5->setTitle('Send engagement letter to client');
        $task5->setDescription('Prepare and send formal engagement letter to Botswana Youth Empowerment Trust');
        $task5->setMatter($this->getReference(MatterFixtures::MATTER_5,Matter::class));
        $task5->setAssignedTo($this->getReference(UserFixtures::SECRETARY_1,User::class));
        $task5->setCreatedBy($this->getReference(UserFixtures::LAWYER_1,User::class)->getUserIdentifier());
        $task5->setPriority(Priority::PRIORITY_LOW);
        $task5->setTaskStatusType(TaskStatusType::TASK_STATUS_COMPLETED);
        $task5->setDueDate(new \DateTimeImmutable('-5 days'));
        $task5->setCompletedAt(new \DateTimeImmutable('-6 days'));
        $manager->persist($task5);

        // Task 6 - Urgent, Overdue
        $task6 = new Task();
        $task6->setTitle('Review contract amendments');
        $task6->setDescription('Review and provide comments on proposed amendments to service agreement');
        $task6->setMatter($this->getReference(MatterFixtures::MATTER_5,Matter::class));
        $task6->setAssignedTo($this->getReference(UserFixtures::LAWYER_1,User::class));
        $task6->setCreatedBy($this->getReference(UserFixtures::LAWYER_2,User::class)->getUserIdentifier());
        $task6->setPriority(Priority::PRIORITY_URGENT);
        $task6->setTaskStatusType(TaskStatusType::TASK_STATUS_PENDING);
        $task6->setDueDate(new \DateTimeImmutable('-2 days')); // Overdue!
        $manager->persist($task6);

        // Task 7 - Medium, Completed
        $task7 = new Task();
        $task7->setTitle('File consent order with court');
        $task7->setDescription('Submit signed consent order to family court for sealing');
        $task7->setMatter($this->getReference(MatterFixtures::MATTER_4,Matter::class));
        $task7->setAssignedTo($this->getReference(UserFixtures::SECRETARY_2,User::class));
        $task7->setCreatedBy($this->getReference(UserFixtures::LAWYER_2,User::class)->getUserIdentifier());
        $task7->setPriority(Priority::PRIORITY_MEDIUM);
        $task7->setTaskStatusType(TaskStatusType::TASK_STATUS_COMPLETED);
        $task7->setDueDate(new \DateTimeImmutable('-30 days'));
        $task7->setCompletedAt(new \DateTimeImmutable('-32 days'));
        $manager->persist($task7);

        // Task 8 - High, In Progress
        $task8 = new Task();
        $task8->setTitle('Conduct legal research on dismissal procedures');
        $task8->setDescription('Research Employment Act provisions and case law on procedural fairness in dismissals');
        $task8->setMatter($this->getReference(MatterFixtures::MATTER_2,Matter::class));
        $task8->setAssignedTo($this->getReference(UserFixtures::LAWYER_2,User::class));
        $task8->setCreatedBy($this->getReference(UserFixtures::LAWYER_2,User::class)->getUserIdentifier());
        $task8->setPriority(Priority::PRIORITY_HIGH);
        $task8->setTaskStatusType(TaskStatusType::TASK_STATUS_IN_PROGRESS);
        $task8->setDueDate(new \DateTimeImmutable('+4 days'));
        $manager->persist($task8);

        // Task 9 - Low, Pending (No matter linked)
        $task9 = new Task();
        $task9->setTitle('Update office procedures manual');
        $task9->setDescription('Review and update client onboarding procedures in the office manual');
        $task9->setMatter($this->getReference(MatterFixtures::MATTER_2,Matter::class));
        $task9->setAssignedTo($this->getReference(UserFixtures::SECRETARY_1,User::class));
        $task9->setCreatedBy($this->getReference(UserFixtures::ADMIN_USER,User::class)->getUserIdentifier());
        $task9->setPriority(Priority::PRIORITY_LOW);
        $task9->setTaskStatusType(TaskStatusType::TASK_STATUS_PENDING);
        $task9->setDueDate(new \DateTimeImmutable('+14 days'));
        $manager->persist($task9);

        // Task 10 - Medium, Pending
        $task10 = new Task();
        $task10->setTitle('Obtain property valuation report');
        $task10->setDescription('Commission independent valuation of Plot 567 from registered valuer');
        $task10->setMatter($this->getReference(MatterFixtures::MATTER_3,Matter::class));
        $task10->setAssignedTo($this->getReference(UserFixtures::LAWYER_1,User::class));
        $task10->setCreatedBy($this->getReference(UserFixtures::LAWYER_1,User::class)->getUserIdentifier());
        $task10->setPriority(Priority::PRIORITY_MEDIUM);
        $task10->setTaskStatusType(TaskStatusType::TASK_STATUS_PENDING);
        $task10->setDueDate(new \DateTimeImmutable('+10 days'));
        $manager->persist($task10);

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
