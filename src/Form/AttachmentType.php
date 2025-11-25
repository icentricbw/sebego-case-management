<?php

namespace App\Form;

use App\Entity\Document;
use App\Enum\DocumentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('imageFile', VichFileType::class)
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'label' => 'Upload Document',
                'help' => 'Accepted formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max 10MB)',
                'attr' => [
                    'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG)',
                    ])
                ],
            ])
            ->add('documentType', EnumType::class, [
                'class' => DocumentType::class,
                'label' => 'Document Type',
                'required' => true,
                'placeholder' => '-- Select document type --',
                'choice_label' => function (DocumentType $type): string {
                    return str_replace('_', ' ', $type->value);
                },
                'help' => 'Select the type of document you are uploading',
            ])
            /*->add('documentType', EnumType::class, [
                'class' => DocumentType::class,
                'label' => 'Document Type',
                'required' => true,
                'placeholder' => 'Select document type',
                'choice_label' => fn (DocumentType $type) => $type->value,
                // Or if you have a custom label method in your enum:
                // 'choice_label' => fn (DocumentType $type) => $type->getLabel(),
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
