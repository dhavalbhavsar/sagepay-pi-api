<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction;

/**
 * Basic Transaction representation.
 */
interface TransactionRequestInterface
{
    public function getTransactionType(): string;

    /**
     * Gets the vendor transmit code.
     */
    public function getVendorTxCode(): ?string;

    /**
     * Sets the vendor transmit code.
     *
     * @return static
     */
    public function setVendorTxCode(string $vendorTxCode);

    /**
     * Gets the description.
     */
    public function getDescription(): ?string;
}
