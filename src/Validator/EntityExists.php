<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class EntityExists extends Constraint
{
    public string $message = 'The {{ entity }} with id "{{ id }}" does not exist.';

    public function __construct(
        public string $entityClass,
        public string $field = 'id',
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);

        if ($message !== null) {
            $this->message = $message;
        }
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
