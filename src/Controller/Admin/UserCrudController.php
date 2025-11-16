<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')
            ->setSearchFields(['email', 'firstName', 'lastName', 'userType'])
            ->setDefaultSort(['lastName' => 'ASC'])
            ->setPageTitle('index', 'System Users')
            ->setPageTitle('new', 'Create New User')
            ->setPageTitle('edit', fn (User $user) => sprintf('Edit User: %s', $user->getFullName()));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('userType')->setChoices([
                'Lawyer' => 'LAWYER',
                'Secretary' => 'SECRETARY',
                'Admin' => 'ADMIN',
            ]))
            ->add(BooleanFilter::new('isActive'))
            ->add('email');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield EmailField::new('email')
            ->setColumns(6)
            ->setRequired(true);

        yield TextField::new('password')
            ->setFormType(PasswordType::class)
            ->setColumns(6)
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms()
            ->setHelp('Leave blank to keep current password');

        yield TextField::new('firstName')
            ->setColumns(4)
            ->setRequired(true);

        yield TextField::new('lastName')
            ->setColumns(4)
            ->setRequired(true);

        yield BooleanField::new('isActive')
            ->setColumns(6)
            ->renderAsSwitch(false);

        $roles = ['ROLE_SUPER_ADMIN',
            'ROLE_ADMIN',
            'ROLE_SECRETARY',
            'ROLE_ATTORNEY','ROLE_CLIENT','ROLE_REP','ROLE_FINANCE'];
        yield ChoiceField::new('roles')
            ->setChoices(array_combine($roles, $roles))
            ->allowMultipleChoices()
            ->renderExpanded()->renderAsBadges();

    }
}
