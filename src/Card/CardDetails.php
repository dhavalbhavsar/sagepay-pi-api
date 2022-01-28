<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Card;

/**
 * Default implementation of CardDetailsInterface.
 */
class CardDetails implements CardDetailsInterface
{
    private ?string $cardholderName = null;

    private ?string $cardNumber = null;

    private ?string $expiryDate = null;

    private ?string $securityCode = null;

    /**
     * A static method to convert CardDetailsInterface into array for api purposes.
     *
     * @return array{
     *     'cardholderName': ?string,
     *     'cardNumber': ?string,
     *     'expiryDate': ?string,
     *     'securityCode'?: string
     * }
     */
    public static function toArray(CardDetailsInterface $card): array
    {
        $array = [];

        $array['cardholderName'] = $card->getCardholderName();
        $array['cardNumber'] = $card->getCardNumber();
        $array['expiryDate'] = $card->getExpiryDate();

        if ($securityCode = $card->getSecurityCode()) {
            $array['securityCode'] = $securityCode;
        }

        return $array;
    }

    public function getCardholderName(): ?string
    {
        return $this->cardholderName;
    }

    /**
     * @return static
     */
    public function setCardholderName(string $cardholderName)
    {
        $this->cardholderName = $cardholderName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    /**
     * @return static
     */
    public function setCardNumber(string $cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpiryDate(): ?string
    {
        return $this->expiryDate;
    }

    /**
     * @return static
     */
    public function setExpiryDate(string $expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecurityCode(): ?string
    {
        return $this->securityCode;
    }

    /**
     * @return static
     */
    public function setSecurityCode(string $securityCode)
    {
        $this->securityCode = $securityCode;

        return $this;
    }
}
