<?php

namespace App\Controller\Admin;

use App\Entity\CommunicationLog;
use App\Enum\CommunicationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class CommunicationLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommunicationLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Communication Log')
            ->setEntityLabelInPlural('Communication Logs')
            ->setSearchFields(['subject', 'summary'])
            ->setDefaultSort(['communicationDate' => 'DESC'])
            ->setPaginatorPageSize(40)
            ->setPageTitle('index', 'Client Communication History');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('communicationType')->setChoices([
                'Meeting' => CommunicationType::COMM_TYPE_MEETING,
                'Phone Call' => CommunicationType::COMM_TYPE_PHONE_CALL,
                'Email' => CommunicationType::COMM_TYPE_EMAIL,
                'SMS' => CommunicationType::COMM_TYPE_SMS,
                'Video Call' => CommunicationType::COMM_TYPE_VIDEO_CALL,
                'Letter' => CommunicationType::COMM_TYPE_LETTER,
            ]))
            ->add(EntityFilter::new('client'))
            ->add(EntityFilter::new('matter'))
            ->add(EntityFilter::new('recordedBy'))
            ->add('communicationDate');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield AssociationField::new('client')
            ->setColumns(6)
            ->setRequired(true)
            ->autocomplete();

        yield AssociationField::new('matter')
            ->setColumns(6)
            ->autocomplete()
            ->setHelp('Optional: Link to specific matter');

        yield ChoiceField::new('communicationType')
            ->setColumns(4)
            ->setChoices([
                'Meeting' => CommunicationType::COMM_TYPE_MEETING,
                'Phone Call' => CommunicationType::COMM_TYPE_PHONE_CALL,
                'Email' => CommunicationType::COMM_TYPE_EMAIL,
                'SMS' => CommunicationType::COMM_TYPE_SMS,
                'Video Call' => CommunicationType::COMM_TYPE_VIDEO_CALL,
                'Letter' => CommunicationType::COMM_TYPE_LETTER,
            ])
            ->renderAsBadges([
                CommunicationType::COMM_TYPE_MEETING->value => 'primary',
                CommunicationType::COMM_TYPE_PHONE_CALL->value => 'success',
                CommunicationType::COMM_TYPE_EMAIL->value => 'info',
                CommunicationType::COMM_TYPE_SMS->value => 'secondary',
                CommunicationType::COMM_TYPE_VIDEO_CALL->value => 'warning',
                CommunicationType::COMM_TYPE_LETTER->value => 'dark',
            ])
            ->setRequired(true);

        yield DateField::new('communicationDate')
            ->setColumns(4)
            ->setRequired(true)
            ->setHelp('When the communication occurred');

        yield IntegerField::new('durationMinutes')
            ->setColumns(4)
            ->setHelp('Duration in minutes (for meetings/calls)');

        yield TextField::new('subject')
            ->setColumns(12)
            ->setRequired(true);

        yield TextareaField::new('summary')
            ->setColumns(12)
            ->setRequired(true)
            ->setNumOfRows(6);

//        yield AssociationField::new('recordedBy')
//            ->onlyOnDetail()
//            ->setFormTypeOption('disabled', true);

        yield DateField::new('createdAt')
            ->hideOnForm()
            ->setHelp('When this was logged in the system');
    }
}
