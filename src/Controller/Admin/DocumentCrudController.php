<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use App\Enum\DocumentType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DocumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Document::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Document')
            ->setEntityLabelInPlural('Documents')
            ->setSearchFields(['imageName', 'client.fullName', 'client.companyName'])
            ->setDefaultSort(['uploadedAt' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->setPageTitle('index', 'Documents')
            ->setPageTitle('new', 'Upload New Document')
            ->setPageTitle('edit', fn (Document $document) => sprintf('Edit: %s', $document->getImageName()))
            ->setPageTitle('detail', fn (Document $document) => sprintf('Document: %s', $document->getImageName()));
    }

    public function configureActions(Actions $actions): Actions
    {
        // Custom action to download file
        $downloadAction = Action::new('download', 'Download', 'fas fa-download')
            ->linkToUrl(function (Document $document): string {
                return $this->generateUrl('admin', [
                    'crudAction' => 'detail',
                    'crudControllerFqcn' => self::class,
                    'entityId' => $document->getId(),
                ]);
            })
            ->displayAsLink()
            ->setCssClass('btn btn-sm btn-primary');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            //->add(Crud::PAGE_DETAIL, Action::EDIT)
            ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT, Action::DELETE])
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER); // Usually only upload one at a time
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        // Client Association
        yield AssociationField::new('client')
            ->setColumns(6)
            ->setRequired(true)
            ->autocomplete()
            ->formatValue(function ($value, Document $document) {
                $client = $document->getClient();
                if (!$client) {
                    return 'N/A';
                }
                return $client->getClientType()->value === 'INDIVIDUAL'
                    ? $client->getFullName()
                    : $client->getCompanyName();
            });

        // Document Type
        yield ChoiceField::new('documentType', 'Type')
            ->setColumns(6)
            ->setChoices([
                'National ID' => DocumentType::ID,
                'Passport' => DocumentType::PASSPORT,
                'Contract' => DocumentType::CONTRACT,
                'Invoice' => DocumentType::INVOICE,
                'Receipt' => DocumentType::RECEIPT,
                'Letter' => DocumentType::LETTER,
                'Court Document' => DocumentType::COURT_DOCUMENT,
                'Evidence' => DocumentType::EVIDENCE,
                'Affidavit' => DocumentType::AFFIDAVIT,
                'Pleading' => DocumentType::PLEADING,
                'Motion' => DocumentType::MOTION,
                'Agreement' => DocumentType::AGREEMENT,
                'Deed' => DocumentType::DEED,
                'Certificate' => DocumentType::CERTIFICATE,
                'Other' => DocumentType::OTHER,
            ])
            ->setRequired(true)
            ->renderAsBadges([
                DocumentType::ID->value => 'primary',
                DocumentType::PASSPORT->value => 'primary',
                DocumentType::CONTRACT->value => 'primary',
                DocumentType::INVOICE->value => 'success',
                DocumentType::RECEIPT->value => 'info',
                DocumentType::LETTER->value => 'secondary',
                DocumentType::COURT_DOCUMENT->value => 'warning',
                DocumentType::EVIDENCE->value => 'danger',
                DocumentType::AFFIDAVIT->value => 'danger',
                DocumentType::PLEADING->value => 'warning',
                DocumentType::MOTION->value => 'warning',
                DocumentType::AGREEMENT->value => 'primary',
                DocumentType::DEED->value => 'dark',
                DocumentType::CERTIFICATE->value => 'dark',
                DocumentType::OTHER->value => 'secondary',
            ]);

        // File Upload Field (only on forms)
        yield TextField::new('imageFile', 'Upload File')
            ->setFormType(VichFileType::class)
            ->setFormTypeOptions([
                'allow_delete' => true,
                'download_uri' => true,
                'download_label' => 'Download current file',
            ])
            ->onlyOnForms()
            ->setColumns(12)
            ->setHelp('Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max 10MB)');

        // File Name (display only)
        yield TextField::new('imageName', 'File Name')
            ->hideOnForm()
            ->formatValue(function ($value, Document $document) {
                if (!$value) {
                    return 'N/A';
                }

                // Get file extension for icon
                $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                $iconMap = [
                    'pdf' => '<i class="fas fa-file-pdf text-danger me-2"></i>',
                    'doc' => '<i class="fas fa-file-word text-primary me-2"></i>',
                    'docx' => '<i class="fas fa-file-word text-primary me-2"></i>',
                    'xls' => '<i class="fas fa-file-excel text-success me-2"></i>',
                    'xlsx' => '<i class="fas fa-file-excel text-success me-2"></i>',
                    'jpg' => '<i class="fas fa-file-image text-info me-2"></i>',
                    'jpeg' => '<i class="fas fa-file-image text-info me-2"></i>',
                    'png' => '<i class="fas fa-file-image text-info me-2"></i>',
                ];

                $icon = $iconMap[$extension] ?? '<i class="fas fa-file text-secondary me-2"></i>';

                return $icon . htmlspecialchars($value);
            })
            ->setTemplatePath('admin/field/file_link.html.twig');

        // File Size
        yield NumberField::new('imageSize', 'File Size')
            ->hideOnForm()
            ->formatValue(function ($value) {
                if (!$value) {
                    return 'N/A';
                }

                if ($value < 1024) {
                    return $value . ' B';
                } elseif ($value < 1048576) {
                    return number_format($value / 1024, 2) . ' KB';
                } else {
                    return number_format($value / 1048576, 2) . ' MB';
                }
            });

        // Upload Date
        yield DateTimeField::new('uploadedAt', 'Uploaded')
            ->hideOnForm()
            ->setFormat('MMM dd, yyyy HH:mm');

        // Created By
        yield TextField::new('createdBy', 'Uploaded By')
            ->hideOnForm()
            ->onlyOnDetail();

        // Timestamps
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
            ->add('documentType')
            ->add('client')
            ->add('imageName')
            ->add('uploadedAt')
            ->add('createdAt');
    }
}
