<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

class ThreeDSRequestorAuthenticationInfo implements ThreeDSRequestorAuthenticationInfoInterface
{
    private ?string $threeDSReqAuthData = null;

    private ?string $threeDSReqAuthMethod = null;

    private ?\DateTimeInterface $threeDSReqAuthTime = null;

    public function getThreeDSReqAuthData(): ?string
    {
        return $this->threeDSReqAuthData;
    }

    /**
     * @return static
     */
    public function setThreeDSReqAuthData(?string $threeDSReqAuthData)
    {
        $this->threeDSReqAuthData = $threeDSReqAuthData;

        return $this;
    }

    public function getThreeDSReqAuthMethod(): ?string
    {
        return $this->threeDSReqAuthMethod;
    }

    /**
     * @return static
     */
    public function setThreeDSReqAuthMethod(?string $threeDSReqAuthMethod)
    {
        $this->threeDSReqAuthMethod = $threeDSReqAuthMethod;

        return $this;
    }

    public function getThreeDSReqAuthTime(): ?\DateTimeInterface
    {
        return $this->threeDSReqAuthTime;
    }

    /**
     * @return static
     */
    public function setThreeDSReqAuthTime(?\DateTimeInterface $threeDSReqAuthTime)
    {
        $this->threeDSReqAuthTime = $threeDSReqAuthTime;

        return $this;
    }
}
