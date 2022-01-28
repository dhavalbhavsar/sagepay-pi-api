<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Card;

interface CardDetailsInterface
{
    /**
     * Gets the cardholder name.
     */
    public function getCardholderName(): ?string;

    /**
     * Gets the card number.
     */
    public function getCardNumber(): ?string;

    /**
     * Gets the expiry date.
     *
     * @return ?string expiry date only digits, no space no slash
     */
    public function getExpiryDate(): ?string;

    /**
     * Gets the security code.
     */
    public function getSecurityCode(): ?string;
}
