<?php

namespace App\Validator;

use App\Repository\SeriesRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidSeriesValidator extends ConstraintValidator
{
    public function __construct(private SeriesRepository $seriesRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidSeries) {
            throw new UnexpectedTypeException($constraint, ValidSeries::class);
        }

        if (null === $value || '' === $value) {
            return; // let NotBlank handle emptiness
        }

        $object = $this->context->getObject();
        $invoiceTypeGetter = $constraint->invoiceTypeGetter;
        if ($invoiceTypeGetter && !is_callable(array($object, $invoiceTypeGetter))) {
            $message = 'Method "%s" used as invoice type getter does not exist in class %s';
            throw new ConstraintDefinitionException(sprintf($message, $invoiceTypeGetter, get_class($object)));
        }

        $exists = $this->seriesRepository->exists($value, $object->$invoiceTypeGetter());
        // or: $this->seriesRepository->findOneBy(['code' => $value]) !== null;

        if (!$exists) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
