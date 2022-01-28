<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Card;

class CardIdentifier implements CardIdentifierInterface
{
    private ?string $id;

    private ?\DateTime $idExpiry;

    private ?string $merchantSessionKey = null;

    private ?string $cardType = null;

    private ?string $lastFourDigits = null;

    private ?string $expiryDate = null;

    private bool $reusable = false;

    public function __construct(string $id, ?\DateTime $idExpiry = null)
    {
        $this->id = $id;
        $this->idExpiry = $idExpiry;
    }

    /**
     * Checks if identifier exipry date is set and if current date is higher than expiry.
     *
     * @return bool true if identifier expired, False otherwise
     */
    public function isIdentifierExpired(): bool
    {
        if ($this->getIdExpiry() && $this->getIdExpiry() < new \DateTime()) {
            return true;
        }

        return false;
    }

    public function getId(): ?string
    {
        if ($this->isIdentifierExpired()) {
            $this->id = null;
            $this->idExpiry = null;

            return null;
        }

        return $this->id;
    }

    public function getIdExpiry(): ?\DateTime
    {
        return $this->idExpiry;
    }

    public function getMerchantSessionKey(): ?string
    {
        return $this->merchantSessionKey;
    }

    /**
     * @return static
     */
    public function setMerchantSessionKey(?string $merchantSessionKey)
    {
        $this->merchantSessionKey = $merchantSessionKey;

        return $this;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    /**
     * @return static
     */
    public function setCardType(string $cardType)
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function getLastFourDigits(): ?string
    {
        return $this->lastFourDigits;
    }

    /**
     * @return static
     */
    public function setLastFourDigits(?string $lastFourDigits)
    {
        $this->lastFourDigits = $lastFourDigits;

        return $this;
    }

    public function getExpiryDate(): ?string
    {
        return $this->expiryDate;
    }

    /**
     * @return static
     */
    public function setExpiryDate(?string $expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    public function isReusable(): bool
    {
        return $this->reusable;
    }

    /**
     * @return static
     */
    public function setReusable(bool $reusable)
    {
        $this->reusable = $reusable;

        if (true === $reusable) {
            $this->idExpiry = null;
        }

        return $this;
    }
}
