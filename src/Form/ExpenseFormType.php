<?php

namespace App\Form;

use App\Enum\ExpenseStatus;
use App\Enum\ExpenseType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExpenseFormType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', ClientAutocompleteField::class, [
                'label' => 'app.ui.client',
            ])
            ->add('type', EnumType::class, [
                'label' => 'app.ui.type',
                'class' => ExpenseType::class,
            ])
            ->add('status', EnumType::class, [
                'label' => 'app.ui.status',
                'class' => ExpenseStatus::class,
            ])
            ->add('documentNumber', TextType::class, [
                'label' => 'app.ui.document_number',
            ])
            ->add('date', DateType::class, [
                'label'  => 'app.ui.date',
                'widget' => 'single_text',
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'app.ui.comment',
            ])
            ->add('total', NumberType::class, [
                'label' => 'app.ui.total',
                'scale' => 2
            ])
            ->add('vat', NumberType::class, [
                'label' => 'app.ui.vat',
                'scale' => 2
            ])
            ->add('file', FileType::class, [
                'label' => 'app.ui.document',
                'attr' => [
                    'accept' => 'application/pdf, image/*',
                ]
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'app_expense';
    }
}
