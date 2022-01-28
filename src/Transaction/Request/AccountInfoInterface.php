<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

interface AccountInfoInterface
{
    public const ACCOUNT_AGE_GUEST_CHECKOUT = 'GuestCheckout';
    public const ACCOUNT_AGE_CREATED_DURING_TRANSACTION = 'CreatedDuringTransaction';
    public const ACCOUNT_AGE_LESS_THAN_THIRTY_DAYS = 'LessThanThirtyDays';
    public const ACCOUNT_AGE_THIRTY_TO_SIXTY_DAYS = 'ThirtyToSixtyDays';
    public const ACCOUNT_AGE_MORE_THAN_SIXTY_DAYS = 'MoreThanSixtyDays';

    public const ACCOUNT_CHANGE_DURING_TRANSACTION = 'DuringTransaction';
    public const ACCOUNT_CHANGE_LESS_THAN_THIRTY_DAYS = 'LessThanThirtyDays';
    public const ACCOUNT_CHANGE_THIRTY_TO_SIXTY_DAYS = 'ThirtyToSixtyDays';
    public const ACCOUNT_CHANGE_MORE_THAN_SIXTY_DAYS = 'MoreThanSixtyDays';

    public const PASSWORD_CHANGE_NO_CHANGE = 'NoChange';
    public const PASSWORD_CHANGE_DURING_TRANSACTION = 'DuringTransaction';
    public const PASSWORD_CHANGE_LESS_THAN_THIRTY_DAYS = 'LessThanThirtyDays';
    public const PASSWORD_CHANGE_THIRTY_TO_SIXTY_DAYS = 'ThirtyToSixtyDays';
    public const PASSWORD_CHANGE_MORE_THAN_SIXTY_DAYS = 'MoreThanSixtyDays';

    public const PAYMENT_ACCOUNT_AGE_GUEST_CHECKOUT = 'GuestCheckout';
    public const PAYMENT_ACCOUNT_AGE_DURING_TRANSACTION = 'DuringTransaction';
    public const PAYMENT_ACCOUNT_AGE_LESS_THAN_THIRTY_DAYS = 'LessThanThirtyDays';
    public const PAYMENT_ACCOUNT_AGE_THIRTY_TO_SIXTY_DAYS = 'ThirtyToSixtyDays';
    public const PAYMENT_ACCOUNT_AGE_MORE_THAN_SIXTY_DAYS = 'MoreThanSixtyDays';

    public const SHIPPING_ADDRESS_USAGE_THIS_TRANSACTION = 'ThisTransaction';
    public const SHIPPING_ADDRESS_USAGE_LESS_THAN_THIRTY_DAYS = 'LessThanThirtyDays';
    public const SHIPPING_ADDRESS_USAGE_THIRTY_TO_SIXTY_DAYS = 'ThirtyToSixtyDays';
    public const SHIPPING_ADDRESS_USAGE_MORE_THAN_SIXTY_DAYS = 'MoreThanSixtyDays';

    public const SHIPPING_NAME_FULL_MATCH = 'FullMatch';
    public const SHIPPING_NAME_NO_MATCH = 'NoMatch';

    public const NOT_SUSPICIOUS_ACTIVITY = 'NotSuspicious';
    public const SUSPICIOUS_ACTIVITY = 'Suspicious';

    public function getChAccAgeInd(): ?string;

    public function getChAccChange(): ?\DateTimeInterface;

    public function getChAccChangeInd(): ?string;

    public function getChAccDate(): ?\DateTimeInterface;

    public function getChAccPwChange(): ?\DateTimeInterface;

    public function getChAccPwChangeInd(): ?string;

    public function getNbPurchaseAccount(): ?int;

    public function getProvisionAttemptsDay(): ?int;

    public function getTxnActivityDay(): ?int;

    public function getTxnActivityYear(): ?int;

    public function getPaymentAccAge(): ?\DateTimeInterface;

    public function getPaymentAccInd(): ?string;

    public function getShipAddressUsage(): ?\DateTimeInterface;

    public function getShipAddressUsageInd(): ?string;

    public function getShipNameIndicator(): ?string;

    public function getSuspiciousAccActivity(): ?string;
}
