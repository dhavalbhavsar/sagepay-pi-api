<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Response;

/**
 * Response for payment, which contains a bit more information than normal response.
 */
class PaymentResponse extends Response
{
    public function getBankResponseCode(): ?string
    {
        return $this->getResponseData()->bankResponseCode;
    }

    public function getCardId(): ?string
    {
        return $this->getResponseData()->card->cardIdentifier;
    }

    public function getAvsCvcCheckStatus(): ?string
    {
        return $this->getResponseData()->avsCvcCheck->status;
    }

    public function getAvsCvcCheckAddress(): ?string
    {
        return $this->getResponseData()->avsCvcCheck->address;
    }

    public function getAvsCvcCheckPostalCode(): ?string
    {
        return $this->getResponseData()->avsCvcCheck->postalCode;
    }

    public function getAvsCvcCheckSecurityCode(): ?string
    {
        return $this->getResponseData()->avsCvcCheck->securityCode;
    }

    public function get3dSecureStatus(): ?string
    {
        return $this->getResponseData()->{'3DSecure'}->status;
    }

    public function getThreeDSecureStatus(): ?string
    {
        return $this->get3dSecureStatus();
    }
}
