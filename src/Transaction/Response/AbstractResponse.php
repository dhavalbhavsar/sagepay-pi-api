<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Response;

/**
 * Contains all common properties of all kind of responses.
 */
abstract class AbstractResponse
{
    private \stdClass $responseData;

    public function __construct(\stdClass $responseData)
    {
        $this->responseData = $responseData;
    }

    public function getId(): string
    {
        return $this->responseData->transactionId;
    }

    public function getStatus(): string
    {
        return $this->responseData->status;
    }

    public function getStatusCode(): string
    {
        return $this->responseData->statusCode;
    }

    public function getStatusDetail(): ?string
    {
        $responseData = $this->responseData;

        return $responseData->statusDetail ?? $responseData->statusMessage ?? null;
    }

    public function getResponseData(): \stdClass
    {
        return $this->responseData;
    }
}
