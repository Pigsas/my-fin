<?php

namespace App\Dto;

use App\Entity\Service;
use App\Enum\UnitType;
use App\Validator\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceLinePostDto
{
    #[EntityExists(Service::class, 'id')]
    public ?int $serviceId = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public float $amount;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: UnitType::class. '::cases')]
    public UnitType $unit;

    #[Assert\NotBlank]
    public float $price;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    public float $discount;
}
