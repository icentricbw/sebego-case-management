<?php

namespace App\Controller\Admin;

use App\Entity\Matter;
use App\Enum\StatusType;
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

class MatterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Matter::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Matter')
            ->setEntityLabelInPlural('Matters')
            ->setSearchFields(['fileNumber', 'description', 'caseType'])
            ->setDefaultSort(['filingDate' => 'DESC'])
            ->setPaginatorPageSize(25)
            ->setPageTitle('index', 'Matters')
            ->setPageTitle('new', 'Create New Matter')
            ->setPageTitle('edit', fn (Matter $matter) => sprintf('Edit Matter: %s', $matter->getFileNumber()))
            ->setPageTitle('detail', fn (Matter $matter) => sprintf('Matter: %s', $matter->getFileNumber()));
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewUpdates = Action::new('viewUpdates', 'Updates', 'fa fa-clipboard-list')
            ->linkToCrudAction('viewUpdates');

        $viewTasks = Action::new('viewTasks', 'Tasks', 'fa fa-tasks')
            ->linkToCrudAction('viewTasks');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_DETAIL, $viewUpdates)
            ->add(Crud::PAGE_DETAIL, $viewTasks)
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_LAWYER');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')->setChoices([
                'Active' => StatusType::ACTIVE,
                'Closed' => StatusType::CLOSED,
                'Archived' => StatusType::ARCHIVED,
            ]))
            ->add('caseType')
            ->add(EntityFilter::new('leadLawyer'))
            ->add(EntityFilter::new('secretary'))
            ->add('filingDate');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield TextField::new('fileNumber')
            ->setColumns(6)
            ->setRequired(true)
            ->setHelp('Unique case/file number');

        yield ChoiceField::new('status')
            ->setColumns(6)
            ->setChoices([
                'Active' => StatusType::ACTIVE,
                'Closed' => StatusType::CLOSED,
                'Archived' => StatusType::ARCHIVED,
            ])
            ->renderAsBadges([
                StatusType::ACTIVE->value => 'success',
                StatusType::CLOSED->value => 'secondary',
                StatusType::ARCHIVED->value => 'warning',
            ]);

        yield TextField::new('caseType')
            ->setColumns(6)
            ->setRequired(true)
            ->setHelp('e.g., CIVIL, CRIMINAL, FAMILY, LABOUR, CONVEYANCING');

        yield DateField::new('filingDate')
            ->setColumns(6)
            ->setRequired(true);

        yield DateField::new('closingDate')
            ->setColumns(6)
            ->hideOnIndex();

        yield TextareaField::new('description')
            ->setColumns(12)
            ->setRequired(true)
            ->hideOnIndex();

        yield AssociationField::new('leadLawyer')
            ->setColumns(6)
            ->setRequired(true)
            ->autocomplete();

        yield AssociationField::new('secretary')
            ->setColumns(6)
            ->autocomplete()
            ->hideOnIndex();

        yield TextareaField::new('notes')
            ->hideOnIndex()
            ->setColumns(12);

        yield AssociationField::new('matterClients', 'Clients')
            ->onlyOnDetail()
            ->setTemplatePath('admin/matter/clients.html.twig');

        yield AssociationField::new('matterLawyers', 'Assigned Lawyers')
            ->onlyOnDetail()
            ->setTemplatePath('admin/matter/lawyers.html.twig');

        yield AssociationField::new('matterUpdates', 'Recent Updates')
            ->onlyOnDetail()
            ->setTemplatePath('admin/matter/updates.html.twig');

        yield AssociationField::new('tasks', 'Tasks')
            ->onlyOnDetail();

        yield DateField::new('createdAt')
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);

        yield DateField::new('updatedAt')
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);
    }
}
