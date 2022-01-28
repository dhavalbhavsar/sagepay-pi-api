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
class RefundRequest extends AbstractTransactionRequest implements RefundRequestInterface
{
    private string $referenceTransactionId;

    public function __construct(string $referenceTransactionId)
    {
        $this->referenceTransactionId = $referenceTransactionId;
    }

    public function getTransactionType(): string
    {
        return self::TYPE_REFUND;
    }

    /**
     * Gets the transactionId of the referenced transaction.
     */
    public function getReferenceTransactionId(): string
    {
        return $this->referenceTransactionId;
    }
}
