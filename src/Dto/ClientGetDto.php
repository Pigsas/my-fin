<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ClientGetDto
{
    #[Assert\Length(min: 1, max: 255)]
    public ?string $name = null;

    #[Assert\Length(min: 1, max: 255)]
    public ?string $code = null;
}
