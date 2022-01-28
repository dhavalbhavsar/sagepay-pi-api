<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

interface MerchantRiskIndicatorInterface
{
    public const DELIVERY_TIMEFRAME_ELECTRONIC_DELIVERY = 'ElectronicDelivery';
    public const DELIVERY_TIMEFRAME_SAME_DAY_SHIPPING = 'SameDayShipping';
    public const DELIVERY_TIMEFRAME_OVERNIGHT_SHIPPING = 'OvernightShipping';
    public const DELIVERY_TIMEFRAME_TWO_DAY_OR_MORE_SHIPPING = 'TwoDayOrMoreShipping';

    public const PRE_ORDER_PURCHASE_IND_MERCHANDISEAVAILABLE = 'MerchandiseAvailable';
    public const PRE_ORDER_PURCHASE_IND_FUTUREAVAILABILITY = 'FutureAvailability';

    public const REORDER_ITEMS_IND_FIRST_TIME_ORDERED = 'FirstTimeOrdered';
    public const REORDER_ITEMS_IND_REORDERED = 'Reordered';

    public const SHIP_INDICATOR_CARDHOLDER_BILLING_ADDRESS = 'CardholderBillingAddress';
    public const SHIP_INDICATOR_OTHER_VERIFIED_ADDRESS = 'OtherVerifiedAddress';
    public const SHIP_INDICATOR_DIFFERENT_TO_CARDHOLDER_BILLING_ADDRESS = 'DifferentToCardholderBillingAddress';
    public const SHIP_INDICATOR_LOCAL_PICK_UP = 'LocalPickUp';
    public const SHIP_INDICATOR_DIGITAL_GOODS = 'DigitalGoods';
    public const SHIP_INDICATOR_NON_SHIPPED_TICKETS = 'NonShippedTickets';
    public const SHIP_INDICATOR_OTHER = 'Other';

    public function getDeliveryEmailAddress(): ?string;

    public function getDeliveryTimeframe(): ?string;

    public function getGiftCardAmount(): ?int;

    public function getGiftCardCount(): ?int;

    public function getPreOrderDate(): ?\DateTimeInterface;

    public function getPreOrderPurchaseInd(): ?string;

    public function getReorderItemsInd(): ?string;

    public function getShipIndicator(): ?string;
}
