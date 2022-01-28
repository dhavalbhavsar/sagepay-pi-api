<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction;

use Lumnn\SagePayPi\AddressInterface;
use Lumnn\SagePayPi\Card\CardIdentifierInterface;
use Lumnn\SagePayPi\Transaction\Request\StrongCustomerAuthenticationInterface;

/**
 * Class for transaction request.
 */
class PaymentRequest extends AbstractTransactionRequest implements PaymentRequestInterface
{
    private string $transactionType = self::TYPE_PAYMENT;

    private ?CardIdentifierInterface $cardIdentifier = null;

    private ?string $currency = null;

    private ?AddressInterface $billingDetails = null;

    private string $entryMethod = self::ENTRY_ECOMMERCE;

    private bool $giftAid = false;

    private string $apply3DSecure = self::SECURITY_CHECK_DEFAULT;

    private string $applyAvsCvcCheck = self::SECURITY_CHECK_DEFAULT;

    private ?string $customerEmail = null;

    private ?string $customerPhone = null;

    private ?AddressInterface $shippingDetails = null;

    private ?string $referrerId = null;

    private ?StrongCustomerAuthenticationInterface $strongCustomerAuthentication = null;

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    /**
     * @return static
     */
    public function setTransactionType(string $transactionType)
    {
        if (!in_array($transactionType, self::TRANSACTION_TYPES)) {
            throw new \InvalidArgumentException(sprintf('Expected one of %s got %s', implode(', ', self::TRANSACTION_TYPES), $transactionType));
        }

        $this->transactionType = $transactionType;

        return $this;
    }

    public function getCardIdentifier(): ?CardIdentifierInterface
    {
        return $this->cardIdentifier;
    }

    /**
     * @return static
     */
    public function setCardIdentifier(CardIdentifierInterface $cardIdentifier)
    {
        $this->cardIdentifier = $cardIdentifier;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @return static
     */
    public function setCurrency(string $currency)
    {
        if (3 !== strlen($currency)) {
            throw new \InvalidArgumentException(sprintf('Currency must be 3 letter long. Got %s', $currency));
        }

        $this->currency = $currency;

        return $this;
    }

    public function getBillingDetails(): ?AddressInterface
    {
        return $this->billingDetails;
    }

    /**
     * @return static
     */
    public function setBillingDetails(AddressInterface $billingDetails)
    {
        $this->billingDetails = $billingDetails;

        return $this;
    }

    public function getEntryMethod(): string
    {
        return $this->entryMethod;
    }

    /**
     * @return static
     */
    public function setEntryMethod(string $entryMethod)
    {
        if (!in_array($entryMethod, self::ENTRY_METHODS)) {
            throw new \InvalidArgumentException(sprintf('Expected one of %s got %s', implode(', ', self::ENTRY_METHODS), $entryMethod));
        }

        $this->entryMethod = $entryMethod;

        return $this;
    }

    public function getGiftAid(): bool
    {
        return $this->giftAid;
    }

    /**
     * @return static
     */
    public function setGiftAid(bool $giftAid)
    {
        $this->giftAid = $giftAid;

        return $this;
    }

    public function getApply3DSecure(): string
    {
        return $this->apply3DSecure;
    }

    /**
     * @return static
     */
    public function setApply3DSecure(string $apply3DSecure)
    {
        if (!in_array($apply3DSecure, self::SECURITY_CHECK_TYPES)) {
            throw new \InvalidArgumentException(sprintf('Expected one of %s got %s', implode(', ', self::SECURITY_CHECK_TYPES), $apply3DSecure));
        }

        $this->apply3DSecure = $apply3DSecure;

        return $this;
    }

    public function getApplyAvsCvcCheck(): string
    {
        return $this->applyAvsCvcCheck;
    }

    /**
     * @return static
     */
    public function setApplyAvsCvcCheck(string $applyAvsCvcCheck)
    {
        if (!in_array($applyAvsCvcCheck, self::SECURITY_CHECK_TYPES)) {
            throw new \InvalidArgumentException(sprintf('Expected one of %s got %s', implode(', ', self::SECURITY_CHECK_TYPES), $applyAvsCvcCheck));
        }

        $this->applyAvsCvcCheck = $applyAvsCvcCheck;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    /**
     * @return static
     */
    public function setCustomerEmail(?string $customerEmail)
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }

    /**
     * @return static
     */
    public function setCustomerPhone(?string $customerPhone)
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }

    public function getShippingDetails(): ?AddressInterface
    {
        return $this->shippingDetails;
    }

    /**
     * @return static
     */
    public function setShippingDetails(?AddressInterface $shippingDetails)
    {
        $this->shippingDetails = $shippingDetails;

        return $this;
    }

    public function getReferrerId(): ?string
    {
        return $this->referrerId;
    }

    /**
     * @return static
     */
    public function setReferrerId(?string $referrerId)
    {
        $this->referrerId = $referrerId;

        return $this;
    }

    public function getStrongCustomerAuthentication(): ?StrongCustomerAuthenticationInterface
    {
        return $this->strongCustomerAuthentication;
    }

    /**
     * @return static
     */
    public function setStrongCustomerAuthentication(?StrongCustomerAuthenticationInterface $strongCustomerAuthentication)
    {
        $this->strongCustomerAuthentication = $strongCustomerAuthentication;

        return $this;
    }
}
