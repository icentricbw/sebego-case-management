<?php

namespace App\Form;

use App\Entity\Document;
use App\Enum\DocumentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichFileType::class)
            ->add('documentType', EnumType::class, [
                'class' => DocumentType::class,
                'label' => 'Document Type',
                'required' => true,
                'placeholder' => 'Select document type',
                'choice_label' => fn (DocumentType $type) => $type->value,
                // Or if you have a custom label method in your enum:
                // 'choice_label' => fn (DocumentType $type) => $type->getLabel(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
