<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction;

/**
 * Refund Transaction Request.
 */
interface RefundRequestInterface extends TransactionRequestInterface
{
    public const TYPE_REFUND = 'Refund';

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
}
