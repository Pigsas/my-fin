<?php

namespace App\Twig;

use App\Entity\Client;
use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\ResourceActions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;


#[AsLiveComponent('client_form_modal_type', template: 'components/ClientFormModalType.html.twig')]
class ClientFormModalType extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public ?Client $initialFormData = null;

    #[LiveProp]
    public ?string $selectId = null;

    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private readonly RegistryInterface $resourceMetadataRegistry
    )
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientFormType::class, $this->initialFormData ?? new Client());
    }

    #[LiveAction]
    public function create(Request $request): void
    {
        $configuration = $this->requestConfigurationFactory->create(
            $this->resourceMetadataRegistry->get('app.client'),
            $request
        );

        $this->submitForm();
        $form = $this->getForm();
        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();

            $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $client);
            $this->clientRepository->add($client);
            $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $client);
            $this->dispatchBrowserEvent('client-form-modal:close', [
                'id' => $client->getId(),
                'name' => $client->getName(),
            ]);
        }

        $this->formView = null;
        $this->resetForm();
    }
}
