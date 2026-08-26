<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ClientPostDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $name;

    #[Assert\Length(min: 1, max: 255)]
    public ?string $address = null;

    #[Assert\Length(min: 1, max: 255)]
    public ?string $code = null;

    #[Assert\Length(min: 1, max: 255)]
    public ?string $vatCode = null;

    #[Assert\Length(min: 1, max: 255)]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\Length(min: 1, max: 255)]
    public ?string $mobile = null;
}
