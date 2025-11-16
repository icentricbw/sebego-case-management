<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Enum\ClientType;
use App\Enum\IdentificationType;
use App\Form\AttachmentType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClientIndividualCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Individual Client')
            ->setEntityLabelInPlural('Individual Clients')
            ->setSearchFields(['fullName', 'identificationNumber', 'email', 'phone'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->setPageTitle('index', 'Individual Clients')
            ->setPageTitle('new', 'Create New Individual Client')
            ->setPageTitle('edit', fn (Client $client) => sprintf('Edit Client: %s', $client->getFullName()))
            ->setPageTitle('detail', fn (Client $client) => sprintf('Client: %s', $client->getFullName()));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Add detail/view action to index page
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // Add index action to edit and detail pages for easy navigation
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            //->add(Crud::PAGE_DETAIL, Action::EDIT)
            // Reorder actions on index page
            ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT, Action::DELETE]);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // Filter to show only INDIVIDUAL clients
        $qb->andWhere('entity.clientType = :clientType')
            ->setParameter('clientType', ClientType::INDIVIDUAL);

        return $qb;
    }

    public function createEntity(string $entityFqcn): Client
    {
        $client = new Client();
        // Automatically set clientType to INDIVIDUAL
        $client->setClientType(ClientType::INDIVIDUAL);

        return $client;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        // Individual-specific fields
        yield TextField::new('fullName')
            ->setRequired(true)
            ->setColumns(6)
            ->setHelp('Individual\'s full name');

        yield ChoiceField::new('identificationType')
            ->setColumns(3)
            ->setChoices([
                'OMANG' => IdentificationType::OMANG,
                'Passport' => IdentificationType::PASSPORT,
            ])
            ->renderAsBadges([
                IdentificationType::OMANG->value => 'primary',
                IdentificationType::PASSPORT->value => 'info',
            ]);

        yield TextField::new('identificationNumber')
            ->setColumns(3)
            ->setHelp('OMANG or Passport number');

        yield EmailField::new('email')
            ->setRequired(false)
            ->setColumns(6)
            ->setHelp('Email address');

        yield TelephoneField::new('primaryPhone')
            ->setRequired(false)
            ->setColumns(6)
            ->setHelp('Primary phone number');

        yield TelephoneField::new('secondaryPhone')
            ->setRequired(false)
            ->setColumns(6)
            ->hideOnIndex()
            ->setHelp('Secondary phone number');

        yield TextField::new('physicalAddress')
            ->setColumns(6)
            ->hideOnIndex()
            ->setRequired(false)
            ->setHelp('Physical/residential address');

        yield TextField::new('postalAddress')
            ->hideOnIndex()
            ->setColumns(6)
            ->setRequired(false)
            ->setHelp('Postal address');

        // Related matters - only show on detail page
        yield AssociationField::new('matterClients', 'Related Matters')
            ->onlyOnDetail()
            ->setTemplatePath('admin/client/matters.html.twig');

        yield CollectionField::new('documents', 'Client Documents')
            ->setEntryType(AttachmentType::class)
            ->hideOnIndex()
            ->onlyOnForms(); // Show on new/edit forms

// Add this for the detail page
        yield AssociationField::new('documents', 'Documents')
            ->onlyOnDetail()
            ->setTemplatePath('admin/client/documents.html.twig');


        // Timestamps
        yield DateTimeField::new('createdAt')
            ->hideOnForm()
            ->onlyOnDetail();

        yield DateTimeField::new('updatedAt')
            ->hideOnForm()
            ->onlyOnDetail();

        // DO NOT include organization fields here
        // - companyName
        // - registrationNumber
        // - authorizedRepresentativeName
        // - authorizedRepresentativePhone
        // - authorizedRepresentativeEmail
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('fullName')
            ->add('identificationNumber')
            ->add('identificationType')
            ->add('email')
            ->add('primaryPhone')
            ->add('createdAt');
    }
}
