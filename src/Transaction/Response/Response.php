<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Response;

/**
 * Basic response class containing all basic information about transaction.
 */
class Response extends AbstractResponse
{
    public function getType(): string
    {
        return $this->getResponseData()->transactionType;
    }

    public function getRetrievalReference(): ?int
    {
        return $this->getResponseData()->retrievalReference ?? null;
    }

    public function getBankAuthorisationCode(): ?string
    {
        return $this->getResponseData()->bankAuthorisationCode ?? null;
    }

    public function getCardType(): string
    {
        return $this->getResponseData()->card->cardType;
    }

    public function getCardLastFourDigits(): string
    {
        return $this->getResponseData()->card->lastFourDigits;
    }

    public function getCardExpiryDate(): string
    {
        return $this->getResponseData()->card->expiryDate;
    }

    public function getTotalAmount(): int
    {
        return $this->getResponseData()->amount->totalAmount;
    }

    public function getSaleAmount(): int
    {
        return $this->getResponseData()->amount->saleAmount;
    }

    public function getSurchargeAmount(): int
    {
        return $this->getResponseData()->amount->surchargeAmount;
    }

    public function getCurrency(): string
    {
        return $this->getResponseData()->currency;
    }
}
