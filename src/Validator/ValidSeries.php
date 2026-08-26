<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ValidSeries extends Constraint
{
    public string $message = 'The selected series "{{ value }}" is not valid.';

    public function __construct(
        public ?string $invoiceTypeGetter = null,
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
