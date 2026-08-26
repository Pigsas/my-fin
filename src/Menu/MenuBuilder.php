<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\ItemInterface;
use Sylius\AdminUi\Knp\Menu\MenuBuilderInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(decorates: 'sylius_admin_ui.knp.menu_builder')]
final readonly class MenuBuilder implements MenuBuilderInterface
{
    public function __construct(
        private readonly MenuBuilderInterface $menuBuilder,
    ) {
    }

    public function createMenu(array $options): ItemInterface
    {
        $menu = $this->menuBuilder->createMenu($options);

        $menu
            ->addChild('dashboard', [
                'route' => 'sylius_admin_ui_dashboard',
            ])
            ->setLabel('sylius.ui.dashboard')
            ->setLabelAttribute('icon', 'tabler:dashboard')
        ;

        $menu
            ->addChild('users', [
                'route' => 'app_dashboard_client_index',
            ])
            ->setLabel('sylius.ui.clients')
            ->setLabelAttribute('icon', 'tabler:users')
        ;

        $menu
            ->addChild('invoices', [
                'route' => 'app_dashboard_invoice_index',
            ])
            ->setLabel('sylius.ui.invoices')
            ->setLabelAttribute('icon', 'tabler:invoice')
        ;

        $menu
            ->addChild('services', [
                'route' => 'app_dashboard_service_index',
            ])
            ->setLabel('sylius.ui.services')
            ->setLabelAttribute('icon', 'tabler:box')
        ;

        $menu
            ->addChild('expenses', [
                'route' => 'app_dashboard_expense_index',
            ])
            ->setLabel('sylius.ui.expenses')
            ->setLabelAttribute('icon', 'tabler:report-money')
        ;

        $menu
            ->addChild('journal', [
                'route' => 'app_journal',
            ])
            ->setLabel('sylius.ui.journal')
            ->setLabelAttribute('icon', 'tabler:book')
        ;

        $menu
            ->addChild('series', [
                'route' => 'app_dashboard_series_index',
            ])
            ->setLabel('app.ui.series')
            ->setLabelAttribute('icon', 'tabler:asterisk')
        ;

        $menu
            ->addChild('settings', [
                'route' => 'app_settings',
            ])
            ->setLabel('app.ui.settings')
            ->setLabelAttribute('icon', 'tabler:settings')
        ;


        return $menu;
    }
}
