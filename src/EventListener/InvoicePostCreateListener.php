<?php

namespace App\EventListener;

use App\Entity\Invoice;
use App\Enum\InvoiceStatus;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(
    event: 'app.invoice.post_create',
)]
final class InvoicePostCreateListener
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(ResourceControllerEvent $event): void
    {
        $invoice = $event->getSubject();

        if ($invoice instanceof Invoice) {

            if ($series = $invoice->getSeries()) {
                $series->incrementCounter();
                $this->entityManager->persist($series);
                $this->entityManager->flush();
            }
        }
    }
}
