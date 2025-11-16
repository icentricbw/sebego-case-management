<?php

namespace App\Controller\Admin;

use App\Entity\Task;
use App\Enum\Priority;
use App\Enum\TaskStatusType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class TaskCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Task::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Task')
            ->setEntityLabelInPlural('Tasks')
            ->setSearchFields(['title', 'description'])
            ->setDefaultSort(['dueDate' => 'ASC', 'priority' => 'DESC'])
            ->setPaginatorPageSize(50)
            ->setPageTitle('index', 'Tasks')
            ->setPageTitle('new', 'Create New Task');
    }

    public function configureActions(Actions $actions): Actions
    {
        $markComplete = Action::new('markComplete', 'Mark Complete', 'fa fa-check')
            ->linkToCrudAction('markComplete')
            ->displayIf(static function (Task $task) {
                return $task->getStatus() !== TaskStatusType::TASK_STATUS_COMPLETED;
            });

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $markComplete)
            ->add(Crud::PAGE_DETAIL, $markComplete);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')->setChoices([
                'Pending' => TaskStatusType::TASK_STATUS_PENDING,
                'In Progress' => TaskStatusType::TASK_STATUS_IN_PROGRESS,
                'Completed' => TaskStatusType::TASK_STATUS_COMPLETED,
            ]))
            ->add(ChoiceFilter::new('priority')->setChoices([
                'Low' => Priority::PRIORITY_LOW,
                'Medium' => Priority::PRIORITY_MEDIUM,
                'High' => Priority::PRIORITY_HIGH,
                'Urgent' => Priority::PRIORITY_URGENT,
            ]))
            ->add(EntityFilter::new('assignedTo'))
            ->add(EntityFilter::new('matter'))
            ->add('dueDate');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield TextField::new('title')
            ->setColumns(12)
            ->setRequired(true);

        yield TextareaField::new('description')
            ->setColumns(12)
            ->hideOnIndex()
            ->setNumOfRows(4);

        yield AssociationField::new('matter')
            ->setColumns(6)
            ->autocomplete()
            ->setHelp('Optional: Link to specific matter');

        yield AssociationField::new('assignedTo')
            ->setColumns(6)
            ->setRequired(true)
            ->autocomplete();

        yield ChoiceField::new('priority')
            ->setColumns(3)
            ->setChoices([
                'Low' => Priority::PRIORITY_LOW,
                'Medium' => Priority::PRIORITY_MEDIUM,
                'High' => Priority::PRIORITY_HIGH,
                'Urgent' => Priority::PRIORITY_URGENT,
            ])
            ->renderAsBadges([
                Priority::PRIORITY_LOW->value => 'secondary',
                Priority::PRIORITY_MEDIUM->value => 'info',
                Priority::PRIORITY_HIGH->value => 'warning',
                Priority::PRIORITY_URGENT->value => 'danger',
            ])
            ->setRequired(true);

        yield ChoiceField::new('status')
            ->setColumns(3)
            ->setChoices([
                'Pending' => TaskStatusType::TASK_STATUS_PENDING,
                'In Progress' => TaskStatusType::TASK_STATUS_IN_PROGRESS,
                'Completed' => TaskStatusType::TASK_STATUS_COMPLETED,
            ])
            ->renderAsBadges([
                TaskStatusType::TASK_STATUS_PENDING->value => 'light',
                TaskStatusType::TASK_STATUS_IN_PROGRESS->value => 'primary',
                TaskStatusType::TASK_STATUS_COMPLETED->value => 'success',
            ])
            ->setRequired(true);

        yield DateField::new('dueDate')
            ->setColumns(3)
            ->setHelp('Deadline for this task');

        yield DateField::new('completedAt')
            ->setColumns(3)
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);

        yield AssociationField::new('createdBy')
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);

        yield DateField::new('createdAt')
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);
    }

    public function markComplete($entityManager, $entity): void
    {
        $entity->setStatus(TaskStatusType::TASK_STATUS_COMPLETED);
        $entity->setCompletedAt(new \DateTimeImmutable());
        $entityManager->flush();

        $this->addFlash('success', 'Task marked as completed!');
    }
}
