<?php

namespace App\Twig;

use Sylius\BootstrapAdminUi\Twig\Component\UserDropdownComponent as ParentUserDropdownComponent;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\Routing\RouterInterface;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

class UserDropdownComponent
{
    public function __construct(
        #[AutowireDecorated]
        private readonly ParentUserDropdownComponent $inner,
        private readonly RouterInterface $router,
    ) {
    }

    #[ExposeInTemplate(name: 'user')]
    public function getUser()
    {
        return $this->inner->getUser();
    }

    #[ExposeInTemplate(name: 'menu_items')]
    public function getMenuItems(): array
    {
        return array_merge([
            [
                'title' => 'app.ui.profile',
                'url' => $this->router->generate('app_profile'),
                'icon' => 'tabler:user',
            ],
        ], $this->inner->getMenuItems());
    }
}
