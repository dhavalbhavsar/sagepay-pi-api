<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

class MerchantRiskIndicator implements MerchantRiskIndicatorInterface
{
    private ?string $deliveryEmailAddress = null;

    private ?string $deliveryTimeframe = null;

    private ?int $giftCardAmount = null;

    private ?int $giftCardCount = null;

    private ?\DateTimeInterface $preOrderDate = null;

    private ?string $preOrderPurchaseInd = null;

    private ?string $reorderItemsInd = null;

    private ?string $shipIndicator = null;

    public function getDeliveryEmailAddress(): ?string
    {
        return $this->deliveryEmailAddress;
    }

    /**
     * @return static
     */
    public function setDeliveryEmailAddress(?string $deliveryEmailAddress)
    {
        $this->deliveryEmailAddress = $deliveryEmailAddress;

        return $this;
    }

    public function getDeliveryTimeframe(): ?string
    {
        return $this->deliveryTimeframe;
    }

    /**
     * @return static
     */
    public function setDeliveryTimeframe(?string $deliveryTimeframe)
    {
        $this->deliveryTimeframe = $deliveryTimeframe;

        return $this;
    }

    public function getGiftCardAmount(): ?int
    {
        return $this->giftCardAmount;
    }

    /**
     * @return static
     */
    public function setGiftCardAmount(?int $giftCardAmount)
    {
        $this->giftCardAmount = $giftCardAmount;

        return $this;
    }

    public function getGiftCardCount(): ?int
    {
        return $this->giftCardCount;
    }

    /**
     * @return static
     */
    public function setGiftCardCount(?int $giftCardCount)
    {
        $this->giftCardCount = $giftCardCount;

        return $this;
    }

    public function getPreOrderDate(): ?\DateTimeInterface
    {
        return $this->preOrderDate;
    }

    /**
     * @return static
     */
    public function setPreOrderDate(?\DateTimeInterface $preOrderDate)
    {
        $this->preOrderDate = $preOrderDate;

        return $this;
    }

    public function getPreOrderPurchaseInd(): ?string
    {
        return $this->preOrderPurchaseInd;
    }

    /**
     * @return static
     */
    public function setPreOrderPurchaseInd(?string $preOrderPurchaseInd)
    {
        $this->preOrderPurchaseInd = $preOrderPurchaseInd;

        return $this;
    }

    public function getReorderItemsInd(): ?string
    {
        return $this->reorderItemsInd;
    }

    /**
     * @return static
     */
    public function setReorderItemsInd(?string $reorderItemsInd)
    {
        $this->reorderItemsInd = $reorderItemsInd;

        return $this;
    }

    public function getShipIndicator(): ?string
    {
        return $this->shipIndicator;
    }

    /**
     * @return static
     */
    public function setShipIndicator(?string $shipIndicator)
    {
        $this->shipIndicator = $shipIndicator;

        return $this;
    }
}
