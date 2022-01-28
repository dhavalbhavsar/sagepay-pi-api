<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Card;

interface CardIdentifierInterface
{
    /**
     * Creates a CardIdentifier.
     *
     * @param string         $id       The identifier
     * @param \DateTime|null $idExpiry The identifier expiry
     */
    public function __construct(string $id, ?\DateTime $idExpiry = null);

    /**
     * Checks if identifier expiry date is set and if current date is higher than expiry.
     *
     * @return bool true if identifier expired, False otherwise
     */
    public function isIdentifierExpired(): bool;

    public function getId(): ?string;

    public function getIdExpiry(): ?\DateTime;

    public function getMerchantSessionKey(): ?string;

    /**
     * @return static
     */
    public function setMerchantSessionKey(?string $merchantSessionKey);

    public function getCardType(): ?string;

    /**
     * @return static
     */
    public function setCardType(string $cardType);

    public function getLastFourDigits(): ?string;

    /**
     * @return static
     */
    public function setLastFourDigits(?string $lastFourDigits);

    public function getExpiryDate(): ?string;

    /**
     * @return static
     */
    public function setExpiryDate(?string $expiryDate);

    public function isReusable(): bool;

    /**
     * @return static
     */
    public function setReusable(bool $reusable);
}
