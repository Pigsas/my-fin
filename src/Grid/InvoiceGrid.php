<?php

namespace App\Grid;

use App\Entity\Invoice;
use App\Enum\InvoiceStatus;
use Sylius\Bundle\GridBundle\Builder\Action\Action;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\DateTimeField;
use Sylius\Bundle\GridBundle\Builder\Field\EnumField;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\EnumFilter;
use Sylius\Bundle\GridBundle\Builder\Filter\StringFilter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    resourceClass: Invoice::class,
    name: 'app_invoice',
)]
final class InvoiceGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
             ->withFilters(
                StringFilter::create('documentNumber')
                    ->setLabel('app.ui.document_number'),
                 EnumFilter::create('status', InvoiceStatus::class)
                     ->setLabel('app.ui.status'),
                 StringFilter::create('client')
                     ->addOption('fields', ['client.name'])
                     ->setLabel('app.ui.client')
            )
            ->withFields(
                StringField::create('documentNumber')
                    ->setLabel('app.ui.document_number')
                    ->setSortable(true),
                DateTimeField::create('date', 'Y-m-d')
                    ->setLabel('app.ui.date')
                    ->setSortable(true),
                StringField::create('client')
                    ->setPath('client.name')
                    ->setLabel('app.ui.client')
                    ->setSortable(true),
                EnumField::create('status')
                    ->setLabel('app.ui.status')
                    ->setSortable(true),
                StringField::create('total')
                    ->setLabel('app.ui.total')
                    ->setSortable(false),
                DateTimeField::create('paidAt', 'Y-m-d')
                    ->setLabel('app.ui.paid')
                    ->setSortable(true),
            )
            ->withMainActions(
                CreateAction::create(),
            )
            ->withItemActions(
                Action::create('printInvoice', 'printInvoice')
                    ->setLabel('app.ui.print_invoice')
                    ->setOptions([
                        'link' => [
                            'route' => 'app_invoice_print',
                            'parameters' => [
                                'id' => 'resource.id'
                            ]
                        ]
                    ])
            )
            ->withItemActions(
                UpdateAction::create(),
            )
            ->addOrderBy('date', 'desc')
            ->addOrderBy('documentNumber', 'desc')
        ;
    }
}
