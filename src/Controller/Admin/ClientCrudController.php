<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Enum\ClientType;
use App\Enum\IdentificationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class ClientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Client')
            ->setEntityLabelInPlural('Clients')
            ->setSearchFields(['fullName', 'companyName', 'identificationNumber', 'email', 'primaryPhone'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->setPageTitle('index', 'Clients')
            ->setPageTitle('new', 'Add New Client')
            ->setPageTitle('edit', fn (Client $client) => sprintf('Edit Client: %s', $client))
            ->setPageTitle('detail', fn (Client $client) => sprintf('Client: %s', $client));
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewMatters = Action::new('viewMatters', 'View Matters', 'fa fa-briefcase')
            ->linkToCrudAction('viewMatters');

        $viewDocuments = Action::new('viewDocuments', 'Documents', 'fa fa-file-alt')
            ->linkToCrudAction('viewDocuments');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_DETAIL, $viewMatters)
            ->add(Crud::PAGE_DETAIL, $viewDocuments)
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('clientType')->setChoices([
                'Individual (OMANG)' => ClientType::INDIVIDUAL,
                'Organization' => ClientType::ORGANIZATION,
            ]))
            ->add(BooleanFilter::new('isActive'))
            ->add('email')
            ->add('createdAt');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield ChoiceField::new('clientType')
            ->setColumns(6)
            ->setChoices([
                'Individual (OMANG)' => ClientType::INDIVIDUAL,
                'Organization' => ClientType::ORGANIZATION,
            ])
            ->renderAsBadges([
                ClientType::INDIVIDUAL->value => 'primary',
                ClientType::ORGANIZATION->value => 'info',
            ])
            ->setRequired(true);

//        yield BooleanField::new('isActive')
//            ->setColumns(6)
//            ->renderAsSwitch(false);

        // Individual fields
        yield TextField::new('fullName')
            ->setColumns(6)
            ->setHelp('For individuals only')
            ->hideOnIndex();

        yield ChoiceField::new('identificationType')
            ->setColumns(3)
            ->setChoices([
                'OMANG' => IdentificationType::OMANG,
                'Passport' => IdentificationType::PASSPORT,
            ])
            ->hideOnIndex();

        yield TextField::new('identificationNumber')
            ->setColumns(3)
            ->setHelp('OMANG or Passport number')
            ->hideOnIndex();

        // Organization fields
        yield TextField::new('companyName')
            ->setColumns(6)
            ->setHelp('For organizations only');

        yield TextField::new('registrationNumber')
            ->setColumns(6)
            ->setHelp('Company registration number')
            ->hideOnIndex();

        yield TextField::new('authorizedRepresentativeName')
            ->setColumns(4)
            ->hideOnIndex();

        yield TelephoneField::new('authorizedRepresentativePhone')
            ->setColumns(4)
            ->hideOnIndex();

        yield EmailField::new('authorizedRepresentativeEmail')
            ->setColumns(4)
            ->hideOnIndex();

        // Common contact fields
        yield TelephoneField::new('primaryPhone')
            ->setColumns(6)
            ->setRequired(true);

        yield TelephoneField::new('secondaryPhone')
            ->setColumns(6)
            ->hideOnIndex();

        yield EmailField::new('email')
            ->setColumns(6);

        // Address fields
        yield TextareaField::new('residentialAddress')
            ->setColumns(6)
            ->setHelp('For individuals')
            ->hideOnIndex();

        yield TextareaField::new('postalAddress')
            ->setColumns(6)
            ->hideOnIndex();

        yield TextareaField::new('physicalAddress')
            ->setColumns(6)
            ->setHelp('For organizations')
            ->hideOnIndex();

        yield TextareaField::new('notes')
            ->setColumns(12)
            ->hideOnIndex();

        // Display name on index
        yield TextField::new('displayName', 'Name')
            ->onlyOnIndex()
            ->setTemplatePath('admin/client/display_name.html.twig');

        // Relationships
        yield AssociationField::new('matterClients', 'Associated Matters')
            ->onlyOnDetail()
            ->setTemplatePath('admin/client/matters.html.twig');

        yield AssociationField::new('documents', 'Documents')
            ->onlyOnDetail();

        yield AssociationField::new('communicationLogs', 'Communication History')
            ->onlyOnDetail()
            ->setTemplatePath('admin/client/communications.html.twig');
    }
}
