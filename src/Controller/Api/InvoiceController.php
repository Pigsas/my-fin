<?php

namespace App\Controller\Api;

use App\Dto\InvoicePatchDto;
use App\Dto\InvoicePostDto;
use App\Entity\Invoice;
use App\Entity\InvoiceLine;
use App\Factory\InvoiceFactory;
use App\Generator\PdfGenerator;
use App\Repository\ClientRepository;
use App\Repository\InvoiceRepository;
use App\Repository\SeriesRepository;
use App\Repository\ServiceRepository;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\ResourceActions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class InvoiceController extends AbstractController
{
    public function __construct(
        private readonly InvoiceRepository $invoiceRepository,
        private readonly ClientRepository $clientRepository,
        private readonly SeriesRepository $seriesRepository,
        private readonly ServiceRepository $serviceRepository,
        private readonly InvoiceFactory $invoiceFactory,
        private readonly ValidatorInterface $validator,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private readonly RegistryInterface $resourceMetadataRegistry
    )
    {
    }

    #[Route('/api/invoice', name: 'app_api_invoice_post', methods: ['POST'])]
    public function postAction(
        Request $request,
        #[MapRequestPayload] InvoicePostDto $invoicePostDto,
    ): Response
    {
        $configuration = $this->requestConfigurationFactory->create(
            $this->resourceMetadataRegistry->get('app.invoice'),
            $request
        );

        $invoice = $this->invoiceFactory->createNew();
        $invoice->setType($invoicePostDto->type);
        $invoice->setStatus($invoicePostDto->status);
        $invoice->setDate(new \DateTime($invoicePostDto->date));
        $invoice->setDueDate(new \DateTime($invoicePostDto->dateDue));
        $invoice->setComment($invoicePostDto->comment);
        $invoice->setClient($this->clientRepository->find($invoicePostDto->clientId));

        if ($invoicePostDto->series) {
            $invoice->setSeries($this->seriesRepository->findOneBy([
                'series' => $invoicePostDto->series,
            ]));
        } else {
            $invoice->setSeries($this->seriesRepository->findOneBy([
                'type' => $invoicePostDto->type,
            ]));
        }
        $invoice->setDocumentNumber($invoice->getSeries()->getNextDocumentNumber());

        foreach ($invoicePostDto->items as $item) {
            $invoiceLine  = new InvoiceLine();

            if ($item->serviceId) {
                $service = $this->serviceRepository->find($item->serviceId);

                $invoiceLine->setName($service->getName());
                $invoiceLine->setPrice($service->getPrice());
            }

            if ($item->name) {
                $invoiceLine->setName($item->name);
            }
            if ($item->price) {
                $invoiceLine->setPrice($item->price);
            }

            $invoiceLine->setAmount($item->amount);
            $invoiceLine->setUnit($item->unit);
            $invoiceLine->setDiscount($item->discount);
            $invoiceLine->calculateTotal();

            $invoice->addLine($invoiceLine);
        }

        $errors = $this->validator->validate($invoice);

        if (count($errors) > 0) {
            throw new ValidationFailedException($invoice, $errors);
        }

        $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $invoice);
        $this->invoiceRepository->add($invoice);
        $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $invoice);

        return $this->json($invoice, 201, [], ['groups' => ['invoice:read']]);
    }

    #[Route('/api/invoice/{id}', name: 'app_api_invoice_patch', methods: ['PATCH'])]
    public function patchAction(
        Request $request,
        Invoice $invoice,
        #[MapRequestPayload] InvoicePatchDto $invoicePatchDto,
    ): Response
    {
        $configuration = $this->requestConfigurationFactory->create(
            $this->resourceMetadataRegistry->get('app.invoice'),
            $request
        );

        if ($invoicePatchDto->clientId) {
            $invoice->setClient($this->clientRepository->find($invoicePatchDto->clientId));
        }

        if ($invoicePatchDto->status) {
            $invoice->setStatus($invoicePatchDto->status);
        }

        if ($invoicePatchDto->date) {
            $invoice->setDate(new \DateTime($invoicePatchDto->date));
        }

        if ($invoicePatchDto->comment) {
            $invoice->setComment($invoicePatchDto->comment);
        }

        if ($invoicePatchDto->dateDue) {
            $invoice->setDueDate(new \DateTime($invoicePatchDto->dateDue));
        }

        if ($invoicePatchDto->items) {
            $invoice->setLines([]);

            foreach ($invoicePatchDto->items as $item) {
                $invoiceLine = new InvoiceLine();

                if ($item->serviceId) {
                    $service = $this->serviceRepository->find($item->serviceId);

                    $invoiceLine->setName($service->getName());
                    $invoiceLine->setPrice($service->getPrice());
                }

                if ($item->name) {
                    $invoiceLine->setName($item->name);
                }
                if ($item->price) {
                    $invoiceLine->setPrice($item->price);
                }

                $invoiceLine->setAmount($item->amount);
                $invoiceLine->setUnit($item->unit);
                $invoiceLine->setDiscount($item->discount);
                $invoiceLine->calculateTotal();

                $invoice->addLine($invoiceLine);
            }
        }

        $errors = $this->validator->validate($invoice);

        if (count($errors) > 0) {
            throw new ValidationFailedException($invoice, $errors);
        }

        $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $invoice);
        $this->invoiceRepository->add($invoice);
        $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $invoice);


        return $this->json($invoice, 201, [], ['groups' => ['invoice:read']]);
    }

    #[Route('/api/invoice/{id}/document', name: 'app_api_invoice_get_document', methods: ['GET'])]
    public function getDocumentAction(
        PdfGenerator $pdfGenerator,
        Invoice $invoice,
    ): Response
    {
        $pdf = $pdfGenerator->getInvoice($invoice);

        if (empty($pdf)) {
            throw new NotFoundHttpException();
        }

        return new Response($pdf, 200, [
            'Content-type' => 'application/pdf',
            'Content-Disposition' => ' filename="' . $invoice->getDocumentNumber() . '.pdf"'
        ]);
    }
}
