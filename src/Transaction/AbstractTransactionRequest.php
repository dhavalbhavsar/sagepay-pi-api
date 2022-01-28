<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction;

/**
 * Class for abstract transaction request.
 */
abstract class AbstractTransactionRequest implements TransactionRequestInterface
{
    private ?string $vendorTxCode = null;

    private int $amount = 0;

    private ?string $description = null;

    public function getVendorTxCode(): ?string
    {
        return $this->vendorTxCode;
    }

    /**
     * @return static
     */
    public function setVendorTxCode(string $vendorTxCode)
    {
        $this->vendorTxCode = $vendorTxCode;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return static
     */
    public function setAmount(int $amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return static
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }
}
