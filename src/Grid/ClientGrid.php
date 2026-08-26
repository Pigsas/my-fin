<?php

namespace App\Grid;

use App\Entity\Client;
use App\Enum\InvoiceStatus;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\EnumFilter;
use Sylius\Bundle\GridBundle\Builder\Filter\StringFilter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    resourceClass: Client::class,
    name: 'app_client',
)]
final class ClientGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->withFilters(
                StringFilter::create('search')
                    ->addOption('fields', ['name', 'email', 'code', 'vatCode', 'phone', 'address', 'contact'])
                    ->setLabel('app.ui.search')
            )
            ->withFields(
                StringField::create('name')
                    ->setLabel('app.ui.name')
                    ->setSortable(true),
                StringField::create('code')
                    ->setLabel('app.ui.code')
                    ->setSortable(true),
                StringField::create('totalInvoices')
                    ->setLabel('app.ui.total_invoices')
                    ->setSortable(false),
            )
            ->withMainActions(
                CreateAction::create(),
            )
            ->withItemActions(
                UpdateAction::create(),
            )
        ;
    }
}
