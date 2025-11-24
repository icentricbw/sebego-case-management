<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\MatterClient;
use App\Enum\ClientRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatterClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => function (Client $client) {
                    return $client->getClientType()->value === 'INDIVIDUAL'
                        ? $client->getFullName()
                        : $client->getCompanyName();
                },
                'label' => 'Client',
                'required' => true,
                'attr' => [
                    'data-widget' => 'select2',
                ],
            ])
            ->add('clientRole', EnumType::class, [
                'class' => ClientRole::class,
                'label' => 'Role in Matter',
                'required' => true,
                'choice_label' => fn (ClientRole $role) => str_replace('_', ' ', $role->value),
                'placeholder' => '-- Select role --',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MatterClient::class,
        ]);
    }
}
