<?php

namespace App\Form;

use App\Enum\InvoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SeriesFormType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EnumType::class, [
                'label' => 'app.ui.type',
                'class' => InvoiceType::class,
                'placeholder' => '',
            ])
            ->add('series', TextType::class, [
                'label' => 'app.ui.series',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'app_series';
    }
}
