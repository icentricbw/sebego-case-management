<?php

namespace App\Controller\Admin;

use App\Entity\FileMovement;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FileMovementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FileMovement::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('File Movement')
            ->setEntityLabelInPlural('File Movements')
            ->setDefaultSort(['movementDate' => 'DESC'])
            ->setPageTitle('index', 'Physical File Movement Log');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield AssociationField::new('matter')
            ->setColumns(6)
            ->setRequired(true)
            ->autocomplete();

        yield DateField::new('movementDate')
            ->setColumns(6)
            ->setRequired(true);

        yield AssociationField::new('fromUser')
            ->setColumns(6)
            ->autocomplete()
            ->setHelp('User who had the file (leave blank if from location)');

        yield TextField::new('fromLocation')
            ->setColumns(6)
            ->setHelp('e.g., Court, Archive, Client (leave blank if from user)');

        yield AssociationField::new('toUser')
            ->setColumns(6)
            ->autocomplete()
            ->setHelp('User receiving the file (leave blank if to location)');

        yield TextField::new('toLocation')
            ->setColumns(6)
            ->setHelp('e.g., Court, Archive, Client (leave blank if to user)');

        yield TextareaField::new('purpose')
            ->setColumns(12)
            ->setHelp('Reason for file transfer');

        yield TextareaField::new('notes')
            ->hideOnIndex();

        yield AssociationField::new('recordedBy')
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);

        yield DateField::new('createdAt')
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);

        // Display from/to descriptions on index
        yield TextField::new('fromDescription', 'From')
            ->onlyOnIndex();

        yield TextField::new('toDescription', 'To')
            ->onlyOnIndex();
    }
}
