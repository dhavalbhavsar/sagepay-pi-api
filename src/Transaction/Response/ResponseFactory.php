<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Response;

class ResponseFactory
{
    public function createResponse(\stdClass $responseData): AbstractResponse
    {
        if ('2007' === $responseData->statusCode) {
            return $this->create3DSv1AuthResponse($responseData);
        }

        if ('2021' === $responseData->statusCode) {
            return $this->create3DSv2AuthResponse($responseData);
        }

        if (isset($responseData->transactionType) && 'Payment' === $responseData->transactionType) {
            return $this->createPaymentResponse($responseData);
        }

        throw new \InvalidArgumentException(sprintf("Couldn't handle response with code %s", $responseData->statusCode));
    }

    public function createPaymentResponse(\stdClass $responseData): PaymentResponse
    {
        return new PaymentResponse($responseData);
    }

    public function create3DSv1AuthResponse(\stdClass $responseData): ThreeDSv1AuthResponse
    {
        return new ThreeDSv1AuthResponse($responseData);
    }

    public function create3DSv2AuthResponse(\stdClass $responseData): ThreeDSv2AuthResponse
    {
        return new ThreeDSv2AuthResponse($responseData);
    }

    public function createRepeatResponse(\stdClass $responseData): Response
    {
        return new Response($responseData);
    }

    public function createRefundResponse(\stdClass $responseData): Response
    {
        return new Response($responseData);
    }
}
