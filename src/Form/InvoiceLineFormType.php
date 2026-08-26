<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\InvoiceLine;
use App\Enum\UnitType;
use App\Repository\ServiceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceLineFormType extends AbstractType
{
    public function __construct(private readonly ServiceRepository $serviceRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('service', ServiceAutocompleteField::class, [
                'label' => 'app.ui.service',
            ])
            ->add('name', TextType::class, [
                'label' => 'app.ui.name',
            ])
            ->add('unit', EnumType::class, [
                'label' => 'app.ui.unit',
                'class' => UnitType::class,
            ])
            ->add('amount', NumberType::class, [
                'label' => 'app.ui.amount',
                'html5' => true,
                'scale' => 3
            ])
            ->add('price', NumberType::class, [
                'label' => 'app.ui.price',
                'html5' => true,
                'scale' => 2
            ])
            ->add('discount', NumberType::class, [
                'label' => 'app.ui.discount',
                'html5' => true,
                'scale' => 2
            ])
            ->add('total', NumberType::class, [
                'label' => 'app.ui.total',
                'html5' => true,
                'scale' => 2
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();

            if ($data instanceof InvoiceLine) {
                $data->calculateTotal();
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if (isset($data['service'])) {

                $service = $this->serviceRepository->find($data['service']);

                if ($service) {
                    $data['name'] = $service->getName();
                    $data['price'] = $service->getPrice();
                    $event->setData($data);
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if (isset($data['amount'], $data['discount'], $data['price'])) {
                 $tmp = new InvoiceLine();
                 $tmp->setAmount($data['amount']);
                 $tmp->setDiscount($data['discount']);
                 $tmp->setPrice($data['price']);
                 $tmp->calculateTotal();

                 $data['total'] = $tmp->getTotal();
                $event->setData($data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceLine::class,
        ]);
    }
}
