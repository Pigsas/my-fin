<?php

namespace App\Grid;

use App\Entity\Series;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\EnumField;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    resourceClass: Series::class,
    name: 'app_series',
)]
final class SeriesGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->withFields(
                EnumField::create('type')
                    ->setLabel('app.ui.invoice_type')
                    ->setSortable(true),
                StringField::create('series')
                    ->setLabel('app.ui.series')
                    ->setSortable(true),
                StringField::create('counter')
                    ->setLabel('app.ui.counter')
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
