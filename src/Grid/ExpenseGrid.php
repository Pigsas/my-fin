<?php

namespace App\Grid;

use App\Entity\Expense;
use App\Enum\InvoiceStatus;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\DateTimeField;
use Sylius\Bundle\GridBundle\Builder\Field\EnumField;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\EnumFilter;
use Sylius\Bundle\GridBundle\Builder\Filter\StringFilter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    resourceClass: Expense::class,
    name: 'app_expense',
)]
final class ExpenseGrid
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
                TwigField::create('status', 'hooks/invoice/grid/field/status.html.twig')
                    ->setLabel('app.ui.status')
                    ->setSortable(true)
                    ->setPath('status'),
                TwigField::create('total', 'hooks/expense/grid/field/money.html.twig')
                    ->setLabel('app.ui.total')
                    ->setSortable(false)
                    ->setPath('total'),
            )
            ->withMainActions(
                CreateAction::create(),
            )
            ->withItemActions(
                UpdateAction::create(),
                DeleteAction::create(),
            )
            ->addOrderBy('date', 'desc')
        ;
    }
}
