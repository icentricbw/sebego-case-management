<?php

namespace App\Controller\Admin;

use App\Entity\MatterUpdate;
use App\Enum\UpdateType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class MatterUpdateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MatterUpdate::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Matter Update')
            ->setEntityLabelInPlural('Matter Updates')
            ->setSearchFields(['content'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(40);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('updateType')->setChoices([
                'Internal Note' => UpdateType::UPDATE_TYPE_INTERNAL_NOTE,
                'Hearing Outcome' => UpdateType::UPDATE_TYPE_HEARING_OUTCOME,
                'Next Step' => UpdateType::UPDATE_TYPE_NEXT_STEP,
                'Client Meeting' => UpdateType::UPDATE_TYPE_CLIENT_MEETING,
                'Court Filing' => UpdateType::UPDATE_TYPE_COURT_FILING,
                'Settlement' => UpdateType::UPDATE_TYPE_SETTLEMENT,
                'Judgment' => UpdateType::UPDATE_TYPE_JUDGMENT,
            ]))
            ->add(EntityFilter::new('matter'))
            ->add(EntityFilter::new('createdBy'))
            ->add('eventDate')
            ->add('createdAt');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
//
//        yield AssociationField::new('matter')
//            ->setColumns(6)
//            ->setRequired(true)
//            ->autocomplete();

        yield ChoiceField::new('updateType')
            ->setColumns(6)
            ->setChoices([
                'Internal Note' => UpdateType::UPDATE_TYPE_INTERNAL_NOTE,
                'Hearing Outcome' => UpdateType::UPDATE_TYPE_HEARING_OUTCOME,
                'Next Step' => UpdateType::UPDATE_TYPE_NEXT_STEP,
                'Client Meeting' => UpdateType::UPDATE_TYPE_CLIENT_MEETING,
                'Court Filing' => UpdateType::UPDATE_TYPE_COURT_FILING,
                'Settlement' => UpdateType::UPDATE_TYPE_SETTLEMENT,
                'Judgment' => UpdateType::UPDATE_TYPE_JUDGMENT,
            ])
            ->renderAsBadges([
                UpdateType::UPDATE_TYPE_INTERNAL_NOTE->value => 'secondary',
                UpdateType::UPDATE_TYPE_HEARING_OUTCOME->value => 'info',
                UpdateType::UPDATE_TYPE_NEXT_STEP->value => 'primary',
                UpdateType::UPDATE_TYPE_CLIENT_MEETING->value => 'success',
                UpdateType::UPDATE_TYPE_COURT_FILING->value => 'warning',
                UpdateType::UPDATE_TYPE_SETTLEMENT->value => 'success',
                UpdateType::UPDATE_TYPE_JUDGMENT->value => 'danger',
            ])
            ->setRequired(true);

        yield DateField::new('eventDate')
            ->setColumns(6)
            ->setHelp('When the event occurred');

        yield TextareaField::new('content')
            ->setColumns(12)
            ->setRequired(true)
            ->setNumOfRows(6);

        yield AssociationField::new('createdBy')
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);

        yield DateField::new('createdAt')
            ->hideOnForm()
            ->setFormTypeOption('disabled', true);
    }
}
