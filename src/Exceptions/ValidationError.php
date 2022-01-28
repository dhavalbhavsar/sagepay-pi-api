<?php

namespace Lumnn\SagePayPi\Exceptions;

class ValidationError implements ValidationErrorInterface
{
    protected int $code;

    protected string $property;

    protected string $description;

    protected ?string $clientMessage;

    public function __construct(int $code, string $property, string $description, ?string $clientMessage)
    {
        $this->code = $code;
        $this->property = $property;
        $this->description = $description;
        $this->clientMessage = $clientMessage;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getClientMessage(): ?string
    {
        return $this->clientMessage;
    }
}
