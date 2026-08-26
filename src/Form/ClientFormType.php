<?php

namespace App\Form;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ClientFormType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'app.ui.name',
            ])
            ->add('address', TextType::class, [
                'label' => 'app.ui.address',
            ])
            ->add('code', TextType::class, [
                'label' => 'app.ui.code',
            ])
            ->add('vatCode', TextType::class, [
                'label' => 'app.ui.vat_code',
            ])
            ->add('contact', TextType::class, [
                'label' => 'app.ui.contact',
            ])
            ->add('email', EmailType::class, [
                'label' => 'app.ui.email',
            ])
            ->add('mobile', TextType::class, [
                'label' => 'app.ui.mobile',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'app_client';
    }
}
