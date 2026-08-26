<?php

namespace App\Form;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ServiceFormType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'app.ui.name',
            ])
            ->add('price', NumberType::class, [
                'label' => 'app.ui.price',
                'scale' => 2
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'app.ui.comment',
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'app_service';
    }
}
