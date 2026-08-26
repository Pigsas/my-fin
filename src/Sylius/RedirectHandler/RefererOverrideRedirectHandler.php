<?php

namespace App\Sylius\RedirectHandler;

use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

final class RefererOverrideRedirectHandler implements RedirectHandlerInterface
{
    public function __construct(
        private readonly RedirectHandlerInterface $inner,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function redirectToReferer(RequestConfiguration $configuration): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();
        $customRedirect = $request?->query->get('redirectUrl');

        if ($customRedirect !== null) {
            return new RedirectResponse($customRedirect);
        }

        return $this->inner->redirectToReferer($configuration);
    }

    // delegate the rest of the interface methods to $this->inner unchanged
    public function redirectToResource(RequestConfiguration $configuration, ResourceInterface $resource): RedirectResponse
    {
        return $this->inner->redirectToResource($configuration, $resource);
    }

    public function redirectToIndex(RequestConfiguration $configuration, ?ResourceInterface $resource = null): RedirectResponse
    {
        return $this->inner->redirectToIndex($configuration, $resource);
    }

    public function redirectToRoute(RequestConfiguration $configuration, string $route, array $parameters = []): RedirectResponse
    {
        return $this->inner->redirectToRoute($configuration, $route, $parameters);
    }

    public function redirect(RequestConfiguration $configuration, $url, int $status = 302): RedirectResponse
    {
        return $this->inner->redirect($configuration, $url, $status);
    }
}
