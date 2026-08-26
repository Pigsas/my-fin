<?php

namespace App\Grid;

use App\Entity\Service;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\StringFilter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    resourceClass: Service::class,
    name: 'app_service',
)]
final class ServiceGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->withFilters(
                StringFilter::create('search')
                    ->addOption('fields', ['name'])
                    ->setLabel('app.ui.search')
            )
            ->withFields(
                StringField::create('name')
                    ->setLabel('app.ui.name')
                    ->setSortable(true),
                StringField::create('price')
                    ->setLabel('app.ui.price')
                    ->setSortable(true),
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
