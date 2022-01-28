<?php

namespace Lumnn\SagePayPi\Exceptions;

/**
 * Interface providing methods for sagepay validation error structure.
 */
interface ValidationErrorInterface
{
    public function getCode(): int;

    public function getProperty(): string;

    public function getDescription(): string;

    public function getClientMessage(): ?string;
}
