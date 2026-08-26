<?php

namespace App\Controller\Api;

use App\Dto\ClientGetDto;
use App\Dto\ClientPostDto;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\ResourceActions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ClientController extends AbstractController
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly ValidatorInterface $validator,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private readonly RegistryInterface $resourceMetadataRegistry
    )
    {
    }

    #[Route('/api/client/find', name: 'app_api_client_get', methods: ['GET'])]
    public function getAction(
        #[MapQueryString] ClientGetDto $clientGetDto,
    ): Response
    {
       $clients = $this->clientRepository->search($clientGetDto);

        return $this->json($clients, 200, [], ['groups' => ['client:read']]);
    }

    #[Route('/api/client', name: 'app_api_client_post', methods: ['POST'])]
    public function postAction(
        Request $request,
        #[MapRequestPayload] ClientPostDto $clientPostDto,
    ): Response
    {
        $configuration = $this->requestConfigurationFactory->create(
            $this->resourceMetadataRegistry->get('app.client'),
            $request
        );

        $client = new Client();
        $client->setName($clientPostDto->name);
        $client->setEmail($clientPostDto->email);
        $client->setCode($clientPostDto->code);
        $client->setAddress($clientPostDto->address);
        $client->setVatCode($clientPostDto->vatCode);
        $client->setMobile($clientPostDto->mobile);

        $errors = $this->validator->validate($client);

        if (count($errors) > 0) {
            throw new ValidationFailedException($client, $errors);
        }

        $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $client);
        $this->clientRepository->add($client);
        $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $client);


        return $this->json($client, 201, [], ['groups' => ['client:read']]);
    }

    #[Route('/api/client/{id}', name: 'app_api_client_patch', methods: ['PATCH'])]
    public function patchAction(
        Request $request,
        Client $client,
        #[MapRequestPayload] ClientPatchDto $clientPatchDto,
    ): Response
    {
        $configuration = $this->requestConfigurationFactory->create(
            $this->resourceMetadataRegistry->get('app.client'),
            $request
        );

        if ($clientPatchDto->name) {
            $client->setName($clientPatchDto->name);
        }

        if ($clientPatchDto->email) {
            $client->setEmail($clientPatchDto->email);
        }

        if ($clientPatchDto->vatCode) {
            $client->setVatCode($clientPatchDto->vatCode);
        }

        if ($clientPatchDto->address) {
            $client->setAddress($clientPatchDto->address);
        }

        if ($clientPatchDto->mobile) {
            $client->setMobile($clientPatchDto->mobile);
        }

        $errors = $this->validator->validate($client);

        if (count($errors) > 0) {
            throw new ValidationFailedException($client, $errors);
        }

        $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $client);
        $this->clientRepository->add($client);
        $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $client);


        return $this->json($client, 201, [], ['groups' => ['client:read']]);
    }
}
