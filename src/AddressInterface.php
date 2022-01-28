<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi;

/**
 * This interface is used in transactions as representation of
 * - billingAddress + customer name
 * - shippingDetails.
 *
 * @see https://test.sagepay.com/documentation/#billing-address-object
 * @see https://test.sagepay.com/documentation/#shipping-details-object
 */
interface AddressInterface
{
    /**
     * Gets the customers first name.
     */
    public function getFirstName(): ?string;

    /**
     * Gets the customers last name.
     */
    public function getLastName(): ?string;

    /**
     * Gets the first address line.
     */
    public function getAddress1(): ?string;

    /**
     * Gets the second address line.
     */
    public function getAddress2(): ?string;

    /**
     * Gets the city.
     */
    public function getCity(): ?string;

    /**
     * Gets the postal code.
     */
    public function getPostalCode(): ?string;

    /**
     * Gets the country.
     */
    public function getCountry(): ?string;

    /**
     * Gets the state.
     */
    public function getState(): ?string;
}
