<?php

namespace App\Controller\Admin;

use App\Entity\Location;
use App\Enum\LocationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LocationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Location::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Location')
            ->setEntityLabelInPlural('Locations')
            ->setSearchFields(['name'])
            ->setDefaultSort(['name' => 'ASC'])
            ->setPaginatorPageSize(30)
            ->setPageTitle('index', 'Physical File Locations')
            ->setPageTitle('new', 'Create New Location')
            ->setPageTitle('edit', fn (Location $location) => sprintf('Edit: %s', $location->getName()))
            ->setPageTitle('detail', fn (Location $location) => sprintf('Location: %s', $location->getName()));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            //->add(Crud::PAGE_DETAIL, Action::EDIT)
            ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT, Action::DELETE]);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name')
            ->setColumns(6)
            ->setRequired(true)
            ->setHelp('e.g., "Main Filing Room", "Secretary Desk", "Partner Office A"');

        yield ChoiceField::new('locationType', 'Type')
            ->setColumns(6)
            ->setChoices([
                'Filing Room' => LocationType::FILING_ROOM,
                'Office' => LocationType::OFFICE,
                'Desk' => LocationType::DESK,
                'Storage' => LocationType::STORAGE,
                'Archive' => LocationType::ARCHIVE,
                'Court' => LocationType::COURT,
                'With Client' => LocationType::CLIENT,
                'External' => LocationType::EXTERNAL,
                'Other' => LocationType::OTHER,
            ])
            ->setRequired(true)
            ->renderAsBadges([
                LocationType::FILING_ROOM->value => 'primary',
                LocationType::OFFICE->value => 'info',
                LocationType::DESK->value => 'secondary',
                LocationType::STORAGE->value => 'warning',
                LocationType::ARCHIVE->value => 'dark',
                LocationType::COURT->value => 'danger',
                LocationType::CLIENT->value => 'success',
                LocationType::EXTERNAL->value => 'info',
                LocationType::OTHER->value => 'secondary',
            ]);

        // Full location display
        /*yield TextField::new('fullLocation', 'Full Address')
            ->onlyOnIndex()
            ->formatValue(function ($value, Location $location) {
                return $location->getFullLocation();
            });*/

        // Related matters count
        /*yield AssociationField::new('matters', 'Current Files')
            ->onlyOnDetail()
            ->formatValue(function ($value, Location $location) {
                return sprintf('%d file(s) currently at this location', $location->getMatters()->count());
            });*/

        // Movement history
        yield AssociationField::new('movementsTo', 'Movement History (To)')
            ->onlyOnDetail()
            ->setTemplatePath('admin/location/movements.html.twig');

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
            ->add('name')
            ->add('locationType');
    }
}
