<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\InvoiceLine;
use App\Entity\Series;
use App\Enum\InvoiceStatus;
use App\Enum\InvoiceType;
use App\Repository\SeriesRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class InvoiceFormType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        private SeriesRepository $seriesRepository,
        array $validationGroups = []
    )
    {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $invoice = $builder->getData();
        $isNew = $invoice instanceof Invoice && null === $invoice->getId();

        $builder
            ->add('client', ClientAutocompleteField::class, [
                'label' => 'app.ui.client',
            ])
            ->add('status', EnumType::class, [
                'label' => 'app.ui.status',
                'class' => InvoiceStatus::class,
            ])
            ->add('documentNumber', TextType::class, [
                'label' => 'app.ui.document_number',
            ])
            ->add('date', DateType::class, [
                'label'  => 'app.ui.date',
                'widget' => 'single_text',
            ])
            ->add('dueDate', DateType::class, [
                'label'  => 'app.ui.due_date',
                'widget' => 'single_text',
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'app.ui.comment',
            ])
            ->add('lines', LiveCollectionType::class, [
                'label' => 'app.ui.lines',
                'entry_type'   => InvoiceLineFormType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype'    => true,
                'prototype_data' => new InvoiceLine(),
            ]);

        if ($isNew) {
            $builder->add('type', EnumType::class, [
                'label' => 'app.ui.type',
                'class' => InvoiceType::class,
            ]);

            $formSeriesModifier = function (FormInterface $form, null|InvoiceType|string $type = null) {
                $form->add('series', EntityType::class, [
                    'label'         => 'app.ui.series',
                    'class'         => Series::class,
                    'query_builder' => function (EntityRepository $er) use ($type): QueryBuilder {
                        return $er->createQueryBuilder('s')
                            ->where('s.type = :type OR s.type IS NULL')
                            ->setParameter('type', $type);
                    },
                    'choice_label'  => 'series',
                    'choice_value'  => 'id',
                ]);
            };


            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formSeriesModifier) {
                $data = $event->getData();
                $formSeriesModifier($event->getForm(), $data?->getType());
            });

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formSeriesModifier) {
                $data = $event->getData();
                $formSeriesModifier($event->getForm(), InvoiceType::tryFrom($data['type'] ?? null));

                if (isset($data['series'])) {
                    $series = $this->seriesRepository->find($data['series']);
                    if ($series->getType()?->value != $data['type'] && $series->getType() !== null) {
                        $data['series'] = $this->seriesRepository->findOneBy(['type' => $data['type']])?->getId();
                        $event->setData($data);
                    }
                }
            });

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formSeriesModifier) {
                $data = $event->getData();

                if ($data instanceof Invoice && $data->getSeries() && empty($data->getDocumentNumber())) {
                    $data->setDocumentNumber($data->getSeries()->getNextDocumentNumber());
                }
            });
            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formSeriesModifier) {
                $data = $event->getData();

                if (isset($data['series'])) {
                    $series = $this->seriesRepository->find($data['series']);
                    if ($series) {
                        dump(2);
                        $data['documentNumber'] = $series->getNextDocumentNumber();
                        $event->setData($data);
                    }
                }
            });
        }
    }

    public function getBlockPrefix(): string
    {
        return 'app_invoice';
    }
}
