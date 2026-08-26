<?php

namespace App\Dto;

use App\Entity\Client;
use App\Enum\InvoiceStatus;
use App\Validator\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class InvoicePatchDto
{
    #[Assert\Length(min: 1, max: 255)]
    #[EntityExists(Client::class, 'id')]
    public ?int $clientId = null;

    #[Assert\Date]
    public ?string $date = null;
    #[Assert\Date]
    public ?string $dateDue = null;

    public ?string $comment = null;

    #[Assert\Choice(callback: InvoiceStatus::class. '::cases')]
    public ?InvoiceStatus $status = null;

    /** @var InvoiceLinePostDto[] */
    #[Assert\Valid]
    #[Assert\Count(min: 1)]
    public ?array $items = null;
}
