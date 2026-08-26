<?php

namespace App\EventListener;

use App\Entity\Invoice;
use App\Enum\InvoiceStatus;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(
    event: 'app.invoice.pre_update',
)]
final class InvoicePreUpdateListener
{
    public function __invoke(ResourceControllerEvent $event): void
    {
        $invoice = $event->getSubject();

        if ($invoice instanceof Invoice) {

            if ($invoice->getStatus() === InvoiceStatus::PAID) {
                $invoice->setPaidAt(new \DateTime());
            } else {
                $invoice->setPaidAt(null);
            }
        }
    }
}
