<?php

namespace App\Controller\Admin;

use App\Entity\CaseType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class CaseTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CaseType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Case Type')
            ->setEntityLabelInPlural('Case Types')
            ->setSearchFields(['name', 'id'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),

            TextField::new('name')
                ->setRequired(true)
                ->setHelp('Name of the case type'),

            AssociationField::new('matters')
                ->setLabel('Related Matters')
                ->onlyOnDetail()
                ->setTemplatePath('admin/field/matters_list.html.twig'), // Optional custom template

            DateTimeField::new('createdAt')
                ->hideOnForm(),

            DateTimeField::new('updatedAt')
                ->hideOnForm(),

            TextField::new('createdBy')
                ->hideOnForm()
                ->onlyOnDetail(),

            TextField::new('updatedBy')
                ->hideOnForm()
                ->onlyOnDetail(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('createdAt')
            ->add('updatedAt');
    }
}
