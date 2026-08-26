<?php

namespace App\Factory;

use App\Entity\Invoice;
use App\Repository\SeriesRepository;
use Sylius\Resource\Factory\FactoryInterface;

class InvoiceFactory implements FactoryInterface
{
    public function __construct(private readonly SeriesRepository $seriesRepository)
    {
    }

    public function createNew(): Invoice
    {
        $invoice = new Invoice();

        $series = $this->seriesRepository->findOneBy(['type' => $invoice->getType()]);
        $invoice->setSeries($series);

        return $invoice;
    }
}
