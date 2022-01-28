<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

class AccountInfo implements AccountInfoInterface
{
    private ?string $chAccAgeInd = null;

    private ?\DateTimeInterface $chAccChange = null;

    private ?string $chAccChangeInd = null;

    private ?\DateTimeInterface $chAccDate = null;

    private ?\DateTimeInterface $chAccPwChange = null;

    private ?string $chAccPwChangeInd = null;

    private ?int $nbPurchaseAccount = null;

    private ?int $provisionAttemptsDay = null;

    private ?int $txnActivityDay = null;

    private ?int $txnActivityYear = null;

    private ?\DateTimeInterface $paymentAccAge = null;

    private ?string $paymentAccInd = null;

    private ?\DateTimeInterface $shipAddressUsage = null;

    private ?string $shipAddressUsageInd = null;

    private ?string $shipNameIndicator = null;

    private ?string $suspiciousAccActivity = null;

    public function getChAccAgeInd(): ?string
    {
        return $this->chAccAgeInd;
    }

    /**
     * @return static
     */
    public function setChAccAgeInd(?string $chAccAgeInd)
    {
        $this->chAccAgeInd = $chAccAgeInd;

        return $this;
    }

    public function getChAccChange(): ?\DateTimeInterface
    {
        return $this->chAccChange;
    }

    /**
     * @return static
     */
    public function setChAccChange(?\DateTimeInterface $chAccChange)
    {
        $this->chAccChange = $chAccChange;

        return $this;
    }

    public function getChAccChangeInd(): ?string
    {
        return $this->chAccChangeInd;
    }

    /**
     * @return static
     */
    public function setChAccChangeInd(?string $chAccChangeInd)
    {
        $this->chAccChangeInd = $chAccChangeInd;

        return $this;
    }

    public function getChAccDate(): ?\DateTimeInterface
    {
        return $this->chAccDate;
    }

    /**
     * @return static
     */
    public function setChAccDate(?\DateTimeInterface $chAccDate)
    {
        $this->chAccDate = $chAccDate;

        return $this;
    }

    public function getChAccPwChange(): ?\DateTimeInterface
    {
        return $this->chAccPwChange;
    }

    /**
     * @return static
     */
    public function setChAccPwChange(?\DateTimeInterface $chAccPwChange)
    {
        $this->chAccPwChange = $chAccPwChange;

        return $this;
    }

    public function getChAccPwChangeInd(): ?string
    {
        return $this->chAccPwChangeInd;
    }

    /**
     * @return static
     */
    public function setChAccPwChangeInd(?string $chAccPwChangeInd)
    {
        $this->chAccPwChangeInd = $chAccPwChangeInd;

        return $this;
    }

    public function getNbPurchaseAccount(): ?int
    {
        return $this->nbPurchaseAccount;
    }

    /**
     * @return static
     */
    public function setNbPurchaseAccount(?int $nbPurchaseAccount)
    {
        $this->nbPurchaseAccount = $nbPurchaseAccount;

        return $this;
    }

    public function getProvisionAttemptsDay(): ?int
    {
        return $this->provisionAttemptsDay;
    }

    /**
     * @return static
     */
    public function setProvisionAttemptsDay(?int $provisionAttemptsDay)
    {
        $this->provisionAttemptsDay = $provisionAttemptsDay;

        return $this;
    }

    public function getTxnActivityDay(): ?int
    {
        return $this->txnActivityDay;
    }

    /**
     * @return static
     */
    public function setTxnActivityDay(?int $txnActivityDay)
    {
        $this->txnActivityDay = $txnActivityDay;

        return $this;
    }

    public function getTxnActivityYear(): ?int
    {
        return $this->txnActivityYear;
    }

    /**
     * @return static
     */
    public function setTxnActivityYear(?int $txnActivityYear)
    {
        $this->txnActivityYear = $txnActivityYear;

        return $this;
    }

    public function getPaymentAccAge(): ?\DateTimeInterface
    {
        return $this->paymentAccAge;
    }

    /**
     * @return static
     */
    public function setPaymentAccAge(?\DateTimeInterface $paymentAccAge)
    {
        $this->paymentAccAge = $paymentAccAge;

        return $this;
    }

    public function getPaymentAccInd(): ?string
    {
        return $this->paymentAccInd;
    }

    /**
     * @return static
     */
    public function setPaymentAccInd(?string $paymentAccInd)
    {
        $this->paymentAccInd = $paymentAccInd;

        return $this;
    }

    public function getShipAddressUsage(): ?\DateTimeInterface
    {
        return $this->shipAddressUsage;
    }

    /**
     * @return static
     */
    public function setShipAddressUsage(?\DateTimeInterface $shipAddressUsage)
    {
        $this->shipAddressUsage = $shipAddressUsage;

        return $this;
    }

    public function getShipAddressUsageInd(): ?string
    {
        return $this->shipAddressUsageInd;
    }

    /**
     * @return static
     */
    public function setShipAddressUsageInd(?string $shipAddressUsageInd)
    {
        $this->shipAddressUsageInd = $shipAddressUsageInd;

        return $this;
    }

    public function getShipNameIndicator(): ?string
    {
        return $this->shipNameIndicator;
    }

    /**
     * @return static
     */
    public function setShipNameIndicator(?string $shipNameIndicator)
    {
        $this->shipNameIndicator = $shipNameIndicator;

        return $this;
    }

    public function getSuspiciousAccActivity(): ?string
    {
        return $this->suspiciousAccActivity;
    }

    /**
     * @return static
     */
    public function setSuspiciousAccActivity(?string $suspiciousAccActivity)
    {
        $this->suspiciousAccActivity = $suspiciousAccActivity;

        return $this;
    }
}
