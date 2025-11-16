<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Enum\ClientType;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClientOrganizationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Organization Client')
            ->setEntityLabelInPlural('Organization Clients')
            ->setSearchFields(['companyName', 'registrationNumber', 'authorizedRepresentativeName', 'email', 'phone'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->setPageTitle('index', 'Organization Clients')
            ->setPageTitle('new', 'Create New Organization Client')
            ->setPageTitle('edit', fn (Client $client) => sprintf('Edit Organization: %s', $client->getCompanyName()))
            ->setPageTitle('detail', fn (Client $client) => sprintf('Organization: %s', $client->getCompanyName()));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
//            ->add(Crud::PAGE_DETAIL, Action::EDIT)
            ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT, Action::DELETE]);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // Filter to show only ORGANIZATION clients
        $qb->andWhere('entity.clientType = :clientType')
            ->setParameter('clientType', ClientType::ORGANIZATION);

        return $qb;
    }

    public function createEntity(string $entityFqcn): Client
    {
        $client = new Client();
        // Automatically set clientType to ORGANIZATION
        $client->setClientType(ClientType::ORGANIZATION);

        return $client;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        // Organization-specific fields
        yield TextField::new('companyName')
            ->setRequired(true)
            ->setColumns(6)
            ->setHelp('Registered company name');

        yield TextField::new('registrationNumber')
            ->setRequired(true)
            ->setColumns(6)
            ->setHelp('Company registration number');

        yield TextField::new('authorizedRepresentativeName')
            ->setLabel('Representative Name')
            ->setRequired(true)
            ->setColumns(6)
            ->setHelp('Name of authorized representative');

        yield TelephoneField::new('authorizedRepresentativePhone')
            ->setLabel('Representative Phone')
            ->setColumns(6)
            ->setRequired(false);

        yield EmailField::new('authorizedRepresentativeEmail')
            ->setLabel('Representative Email')
            ->setColumns(6)
            ->setRequired(false);

        // Contact information
        yield EmailField::new('email')
            ->setLabel('Company Email')
            ->setColumns(6)
            ->setRequired(false);

        yield TelephoneField::new('primaryPhone')
            ->setLabel('Company Phone')
            ->setColumns(6)
            ->setRequired(false);

        yield TextField::new('physicalAddress')
            ->setColumns(6)
            ->hideOnIndex()
            ->setRequired(false);

        yield TextField::new('postalAddress')
            ->setColumns(6)
            ->hideOnIndex()
            ->setRequired(false);

        // Related matters
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

        yield DateTimeField::new('createdAt')
            ->hideOnForm()
            ->onlyOnDetail();

        yield DateTimeField::new('updatedAt')
            ->hideOnForm()
            ->onlyOnDetail();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('companyName')
            ->add('registrationNumber')
            ->add('authorizedRepresentativeName')
            ->add('email')
            ->add('createdAt');
    }
}
