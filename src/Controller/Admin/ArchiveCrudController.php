<?php

namespace App\Controller\Admin;

use App\Entity\Archive;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArchiveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Archive::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Archive')
            ->setEntityLabelInPlural('Archives')
            ->setSearchFields(['boxNumber', 'room', 'cabinet', 'shelf'])
            ->setDefaultSort(['archivedDate' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield AssociationField::new('matter')
            ->setColumns(6)
            ->setRequired(true)
            ->autocomplete();

        yield TextField::new('boxNumber')
            ->setColumns(6)
            ->setRequired(true);

        yield TextField::new('room')
            ->setColumns(3);

        yield TextField::new('cabinet')
            ->setColumns(3);

        yield TextField::new('shelf')
            ->setColumns(3);

        yield TextareaField::new('location')
            ->setColumns(12)
            ->setHelp('Full location description');

        yield DateField::new('archivedDate')
            ->setColumns(6);

        yield TextareaField::new('notes')
            ->hideOnIndex();

        // Display full location on index
        yield TextField::new('fullLocation', 'Location')
            ->onlyOnIndex();
    }
}
