<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

class ThreeDSRequestorPriorAuthenticationInfo implements ThreeDSRequestorPriorAuthenticationInfoInterface
{
    private ?string $threeDSReqPriorAuthData = null;

    private ?string $threeDSReqPriorAuthMethod = null;

    private ?\DateTimeInterface $threeDSReqPriorAuthTime = null;

    private ?string $threeDSReqPriorRef = null;

    public function getThreeDSReqPriorAuthData(): ?string
    {
        return $this->threeDSReqPriorAuthData;
    }

    /**
     * @return static
     */
    public function setThreeDSReqPriorAuthData(?string $threeDSReqPriorAuthData)
    {
        $this->threeDSReqPriorAuthData = $threeDSReqPriorAuthData;

        return $this;
    }

    public function getThreeDSReqPriorAuthMethod(): ?string
    {
        return $this->threeDSReqPriorAuthMethod;
    }

    /**
     * @return static
     */
    public function setThreeDSReqPriorAuthMethod(?string $threeDSReqPriorAuthMethod)
    {
        $this->threeDSReqPriorAuthMethod = $threeDSReqPriorAuthMethod;

        return $this;
    }

    public function getThreeDSReqPriorAuthTime(): ?\DateTimeInterface
    {
        return $this->threeDSReqPriorAuthTime;
    }

    /**
     * @return static
     */
    public function setThreeDSReqPriorAuthTime(?\DateTimeInterface $threeDSReqPriorAuthTime)
    {
        $this->threeDSReqPriorAuthTime = $threeDSReqPriorAuthTime;

        return $this;
    }

    public function getThreeDSReqPriorRef(): ?string
    {
        return $this->threeDSReqPriorRef;
    }

    /**
     * @return static
     */
    public function setThreeDSReqPriorRef(?string $threeDSReqPriorRef)
    {
        $this->threeDSReqPriorRef = $threeDSReqPriorRef;

        return $this;
    }
}
