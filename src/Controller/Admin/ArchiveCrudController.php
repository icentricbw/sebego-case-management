<?php

namespace App\Controller\Admin;

use App\Entity\Archive;
use App\Enum\StatusType;
use Doctrine\ORM\EntityManagerInterface;
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
            ->setSearchFields(['boxNumber', 'room', 'cabinet', 'shelf', 'batch'])
            ->setDefaultSort(['archivedDate' => 'DESC'])
            ->setPaginatorPageSize(30);
    }

    /**
     * Called when creating a new Archive
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Archive $entityInstance */
        if ($entityInstance instanceof Archive) {
            // Update the related Matter status to ARCHIVED
            $matter = $entityInstance->getMatter();
            if ($matter) {
                $matter->setStatusType(StatusType::ARCHIVED);
                $entityManager->persist($matter);
            }
        }

        parent::persistEntity($entityManager, $entityInstance);

        $this->addFlash('success', sprintf(
            'Archive created successfully. Matter "%s" has been marked as ARCHIVED.',
            $entityInstance->getMatter()?->getFileNumber() ?? 'N/A'
        ));
    }

    /**
     * Called when updating an existing Archive
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Archive $entityInstance */
        if ($entityInstance instanceof Archive) {
            // Ensure the related Matter is still marked as ARCHIVED
            $matter = $entityInstance->getMatter();
            if ($matter && $matter->getStatusType() !== StatusType::ARCHIVED) {
                $matter->setStatusType(StatusType::ARCHIVED);
                $entityManager->persist($matter);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);

        $this->addFlash('success', 'Archive updated successfully.');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield AssociationField::new('matter')
            ->setColumns(6)
            ->setRequired(true)
            ->autocomplete()
            ->setHelp('Select the matter to archive. Status will be automatically set to ARCHIVED.');

        yield TextField::new('batch')
            ->hideOnIndex()
            ->setColumns(3)
            ->setHelp('Archive batch code (e.g., A, AI, AE)');

        yield TextField::new('boxNumber')
            ->setColumns(3)
            ->hideOnIndex()
            ->setRequired(true)
            ->setHelp('Box number');

        yield TextField::new('room')
            ->hideOnIndex()
            ->setColumns(3)
            ->setHelp('Storage room');

        yield TextField::new('cabinet')
            ->setColumns(3)
            ->hideOnIndex()
            ->setHelp('Cabinet number');

        yield TextField::new('shelf')
            ->setColumns(3)
            ->hideOnIndex()
            ->setHelp('Shelf number');

        yield DateField::new('archivedDate')
            ->setColumns(3)
            ->setRequired(true)
            ->setHelp('Date archived');

        yield TextareaField::new('location')
            ->setColumns(12)
            ->hideOnIndex()
            ->setHelp('Full location description (e.g., "Storage Room 2, Cabinet 5, Shelf 3")');

        yield TextareaField::new('notes')
            ->setColumns(12)
            ->hideOnIndex()
            ->setHelp('Additional notes about this archive');

        // Display fields on index
        yield TextField::new('matter.fileNumber', 'File Number')
            ->onlyOnIndex();

        yield TextField::new('batch')
            ->onlyOnIndex();

        yield TextField::new('boxNumber')
            ->onlyOnIndex();

        yield TextField::new('location')
            ->onlyOnIndex()
            ->formatValue(function ($value, Archive $archive) {
                $parts = array_filter([
                    $archive->getBatch() ? "Batch {$archive->getBatch()}" : null,
                    $archive->getBoxNumber() ? "Box {$archive->getBoxNumber()}" : null,
                    $archive->getRoom() ? "Room {$archive->getRoom()}" : null,
                    $archive->getCabinet() ? "Cabinet {$archive->getCabinet()}" : null,
                    $archive->getShelf() ? "Shelf {$archive->getShelf()}" : null,
                ]);
                return implode(', ', $parts) ?: 'N/A';
            });

        yield DateField::new('archivedDate')
            ->onlyOnIndex();

        yield DateField::new('createdAt')
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);

        yield DateField::new('updatedAt')
            ->onlyOnDetail()
            ->setFormTypeOption('disabled', true);
    }
}
