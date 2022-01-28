<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction;

use Lumnn\SagePayPi\AddressInterface;

/**
 * Repeat Transaction Request.
 */
interface RepeatRequestInterface extends TransactionRequestInterface
{
    public const TYPE_REPEAT = 'Repeat';

    /**
     * Gets the transactionId of the referenced transaction.
     */
    public function getReferenceTransactionId(): ?string;

    /**
     * Gets the amount charged to the customer in the smallest currency unit.
     * (e.g 100 pence to charge £1.00, or 1 to charge ¥1 (0-decimal currency).
     * By default it should return 0.
     */
    public function getAmount(): int;

    /**
     * Gets the currency of the amount in 3 letter ISO 4217 format.
     */
    public function getCurrency(): ?string;

    /**
     * Gets the shipping details.
     */
    public function getShippingDetails(): ?AddressInterface;

    /**
     * Gets the gift aid.
     * Identifies the customer has ticked a box to indicate that they wish to receive tax back on their donation.
     */
    public function getGiftAid(): bool;
}
