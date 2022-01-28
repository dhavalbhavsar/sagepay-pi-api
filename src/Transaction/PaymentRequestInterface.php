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

interface PaymentRequestInterface extends TransactionRequestInterface
{
    public const TYPE_PAYMENT = 'Payment';
    public const TYPE_DEFERRED = 'Deferred';

    public const TRANSACTION_TYPES = [self::TYPE_PAYMENT, self::TYPE_DEFERRED/*, self::TYPE_REPEAT, self::TYPE_REFUND*/];

    public const ENTRY_ECOMMERCE = 'Ecommerce';
    public const ENTRY_MAIL_ORDER = 'MailOrder';
    public const ENTRY_TELEPHONE_ORDER = 'TelephoneOrder';

    public const ENTRY_METHODS = [self::ENTRY_ECOMMERCE, self::ENTRY_MAIL_ORDER, self::ENTRY_TELEPHONE_ORDER];

    public const SECURITY_CHECK_DEFAULT = 'UseMSPSetting';
    public const SECURITY_CHECK_FORCE = 'Force';
    public const SECURITY_CHECK_DISABLE = 'Disable';
    public const SECURITY_CHECK_FORCE_IGNORING_RULES = 'ForceIgnoringRules';

    public const SECURITY_CHECK_TYPES = [self::SECURITY_CHECK_DEFAULT, self::SECURITY_CHECK_FORCE, self::SECURITY_CHECK_DISABLE, self::SECURITY_CHECK_FORCE_IGNORING_RULES];

    /**
     * Gets the card identifier.
     */
    public function getCardIdentifier(): ?CardIdentifierInterface;

    /**
     * Gets the amount charged to the customer in the smallest currency unit.
     * (e.g 100 pence to charge £1.00, or 1 to charge ¥1 (0-decimal currency).
     * By default it should return 0.
     */
    public function getAmount(): int;

    /**
     * Gets the currency of the amount in 3 letter ISO 4217 format.
     *
     * @return ?string 3 digits
     */
    public function getCurrency(): ?string;

    /**
     * Gets the billing details.
     *
     * @return ?AddressInterface The billing details including address, first and last name
     */
    public function getBillingDetails(): ?AddressInterface;

    /**
     * Gets the entry method.
     *
     * @return string The entry method. Default self::ENTRY_ECOMMERCE
     */
    public function getEntryMethod(): string;

    /**
     * Gets the gift aid.
     * Identifies the customer has ticked a box to indicate that they wish to receive tax back on their donation.
     */
    public function getGiftAid(): bool;

    /**
     * Gets the apply 3d secure.
     */
    public function getApply3DSecure(): string;

    /**
     * Gets the apply avs cvc check.
     */
    public function getApplyAvsCvcCheck(): string;

    /**
     * Gets the customer email.
     */
    public function getCustomerEmail(): ?string;

    /**
     * Gets the customer phone.
     */
    public function getCustomerPhone(): ?string;

    /**
     * Gets the shipping details.
     */
    public function getShippingDetails(): ?AddressInterface;

    /**
     * Gets the referrer identifier.
     */
    public function getReferrerId(): ?string;

    /**
     * Gets the object used for 3-D Secure Version 2.
     */
    public function getStrongCustomerAuthentication(): ?StrongCustomerAuthenticationInterface;
}
