<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi;

/**
 * Basic implementation of AddressInterface.
 */
class Address implements AddressInterface
{
    private ?string $firstName = null;

    private ?string $lastName = null;

    private ?string $address1 = null;

    private ?string $address2 = null;

    private ?string $city = null;

    private ?string $postalCode = null;

    private ?string $country = null;

    private ?string $state = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return static
     */
    public function setFirstName(?string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return static
     */
    public function setLastName(?string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    /**
     * @return static
     */
    public function setAddress1(?string $address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    /**
     * @return static
     */
    public function setAddress2(?string $address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return static
     */
    public function setCity(?string $city)
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @return static
     */
    public function setPostalCode(?string $postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return static
     */
    public function setCountry(?string $country)
    {
        $this->country = $country;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return static
     */
    public function setState(?string $state)
    {
        $this->state = $state;

        return $this;
    }
}
