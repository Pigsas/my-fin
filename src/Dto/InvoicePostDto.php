<?php

namespace App\Dto;

use App\Entity\Client;
use App\Enum\InvoiceStatus;
use App\Enum\InvoiceType;
use App\Validator\EntityExists;
use App\Validator\ValidSeries;
use Symfony\Component\Validator\Constraints as Assert;

class InvoicePostDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[EntityExists(Client::class, 'id')]
    public int $clientId;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: InvoiceType::class . '::cases')]
    public InvoiceType $type;

    #[Assert\NotBlank]
    #[ValidSeries('getInvoiceType')]
    public ?string $series = null;

    #[Assert\Date]
    public ?string $date = null;
    #[Assert\Date]
    public ?string $dateDue = null;

    public ?string $comment = null;
    #[Assert\NotBlank]
    #[Assert\Choice(callback: InvoiceStatus::class. '::cases')]
    public InvoiceStatus $status;

    /** @var InvoiceLinePostDto[] */
    #[Assert\NotBlank]
    #[Assert\Valid]
    #[Assert\Count(min: 1)]
    public array $items;

    public function getInvoiceType(): InvoiceType
    {
        return $this->type;
    }
}
