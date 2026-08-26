<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: ExceptionEvent::class)]
class ValidationExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        // Bail out early if the client didn't ask for JSON
        if (!$this->wantsJson($request)) {
            return;
        }

        $exception = $event->getThrowable();
        $previous = $exception->getPrevious();

        // Case 1: MapRequestPayload validation failure
        if ($exception instanceof ValidationFailedException) {
            $event->setResponse($this->buildValidationErrorResponse($exception->getViolations()));
            return;
        }

        // Case 1: MapRequestPayload validation failure
        if ($previous instanceof ValidationFailedException) {
            $event->setResponse($this->buildValidationErrorResponse($previous->getViolations()));
            return;
        }

        // Case 2: Malformed JSON body
        if ($exception instanceof NotEncodableValueException
            || $previous instanceof NotEncodableValueException
        ) {
            $event->setResponse(new JsonResponse([
                'message' => 'Invalid JSON payload.',
            ], 400));
            return;
        }
    }

    private function wantsJson(Request $request): bool
    {
        // Covers: Accept: application/json
        //         Accept: application/json, text/plain, */*
        // Excludes: Accept: text/html (even if it contains */*)
        return $request->getPreferredFormat() === 'json'
            || str_contains((string) $request->headers->get('Accept'), 'application/json');
    }

    private function buildValidationErrorResponse(ConstraintViolationListInterface $errors): JsonResponse
    {
        return new JsonResponse([
            'errors'  => $this->formatErrors($errors),
        ], 422);
    }

    private function formatErrors(ConstraintViolationListInterface $errors): array
    {
        $formatted = [];
        foreach ($errors as $error) {
            $formatted[$error->getPropertyPath()] = $error->getMessage();
        }
        return $formatted;
    }
}
