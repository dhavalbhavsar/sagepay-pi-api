<?php

namespace Lumnn\SagePayPi\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ValidationException extends ClientException
{
    /**
     * @var ValidationErrorInterface[]
     */
    private $errors = [];

    public function __construct(RequestInterface $request, ResponseInterface $response, \stdClass $decodedBody)
    {
        $this->errors = $this->handleErrors($decodedBody);

        parent::__construct(
            $request,
            $response,
            $decodedBody,
            $this->createMessage(),
        );
    }

    /**
     * Gets the errors.
     *
     * @return ValidationErrorInterface[] the errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return ValidationErrorInterface[]
     */
    private function handleErrors(\stdClass $responseBody): array
    {
        $errors = [];

        if (!is_array($responseBody->errors)) {
            throw new \Exception('Invalid response provided');
        }

        foreach ($responseBody->errors as $error) {
            if (!($error instanceof \stdClass)
                || !is_int($error->code)
                || !is_string($error->property)
                || !is_string($error->description)
            ) {
                throw new \Exception('Invalid response error type or content provided');
            }

            $errors[] = new ValidationError(
                $error->code,
                $error->property,
                $error->description,
                $error->clientMessage ?? null,
            );
        }

        return $errors;
    }

    private function createMessage(): string
    {
        $errors = $this->getErrors();

        $count = count($errors);

        $firstMessage = $errors[0]->getClientMessage() ?? $errors[0]->getDescription();

        if (1 === $count) {
            return 'SagePay PI returned with validation error: '.$firstMessage;
        }

        return sprintf(
            'SagePay PI returned with %s validation errors: [1] %s + %d more',
            $count,
            $firstMessage,
            $count - 1
        );
    }
}
