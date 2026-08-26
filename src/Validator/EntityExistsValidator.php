<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class EntityExistsValidator extends ConstraintValidator
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedTypeException($constraint, EntityExists::class);
        }

        if ($value === null || $value === '') {
            return; // let NotBlank/NotNull handle emptiness
        }

        $repository = $this->em->getRepository($constraint->entityClass);
        $entity = $repository->findOneBy([$constraint->field => $value]);

        if ($entity === null) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ entity }}', $constraint->entityClass)
                ->setParameter('{{ id }}', (string) $value)
                ->addViolation();
        }
    }
}
