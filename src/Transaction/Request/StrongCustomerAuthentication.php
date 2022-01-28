<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

class StrongCustomerAuthentication implements StrongCustomerAuthenticationInterface
{
    // required
    private ?string $notificationURL = null;

    // required
    private ?string $browserIP = null;

    // required
    private ?string $browserAcceptHeader = null;

    // required
    private ?bool $browserJavascriptEnabled = null;

    private ?bool $browserJavaEnabled = null;

    // required
    private ?string $browserLanguage = null;

    private ?string $browserColorDepth = null;

    private ?string $browserScreenHeight = null;

    private ?string $browserScreenWidth = null;

    private ?string $browserTZ = null;

    // required
    private ?string $browserUserAgent = null;

    // required
    private ?string $challengeWindowSize = null;

    private ?string $acctID = null;

    // required
    private ?string $transType = null;

    private ?ThreeDSRequestorAuthenticationInfoInterface $threeDSRequestorAuthenticationInfo = null;

    private ?ThreeDSRequestorPriorAuthenticationInfoInterface $threeDSRequestorPriorAuthenticationInfo = null;

    private ?AccountInfoInterface $acctInfo = null;

    private ?MerchantRiskIndicatorInterface $merchantRiskIndicator = null;

    private ?string $threeDSExemptionIndicator = null;

    private ?string $website = null;

    public function getNotificationURL(): ?string
    {
        return $this->notificationURL;
    }

    /**
     * @return static
     */
    public function setNotificationURL(?string $notificationURL)
    {
        $this->notificationURL = $notificationURL;

        return $this;
    }

    public function getBrowserIP(): ?string
    {
        return $this->browserIP;
    }

    /**
     * @return static
     */
    public function setBrowserIP(?string $browserIP)
    {
        $this->browserIP = $browserIP;

        return $this;
    }

    public function getBrowserAcceptHeader(): ?string
    {
        return $this->browserAcceptHeader;
    }

    /**
     * @return static
     */
    public function setBrowserAcceptHeader(?string $browserAcceptHeader)
    {
        $this->browserAcceptHeader = $browserAcceptHeader;

        return $this;
    }

    public function getBrowserJavascriptEnabled(): ?bool
    {
        return $this->browserJavascriptEnabled;
    }

    /**
     * @return static
     */
    public function setBrowserJavascriptEnabled(?bool $browserJavascriptEnabled)
    {
        $this->browserJavascriptEnabled = $browserJavascriptEnabled;

        return $this;
    }

    public function getBrowserJavaEnabled(): ?bool
    {
        return $this->browserJavaEnabled;
    }

    /**
     * @return static
     */
    public function setBrowserJavaEnabled(?bool $browserJavaEnabled)
    {
        $this->browserJavaEnabled = $browserJavaEnabled;

        return $this;
    }

    public function getBrowserLanguage(): ?string
    {
        return $this->browserLanguage;
    }

    /**
     * @return static
     */
    public function setBrowserLanguage(?string $browserLanguage)
    {
        $this->browserLanguage = $browserLanguage;

        return $this;
    }

    public function getBrowserColorDepth(): ?string
    {
        return $this->browserColorDepth;
    }

    /**
     * @return static
     */
    public function setBrowserColorDepth(?string $browserColorDepth)
    {
        $this->browserColorDepth = $browserColorDepth;

        return $this;
    }

    public function getBrowserScreenHeight(): ?string
    {
        return $this->browserScreenHeight;
    }

    /**
     * @return static
     */
    public function setBrowserScreenHeight(?string $browserScreenHeight)
    {
        $this->browserScreenHeight = $browserScreenHeight;

        return $this;
    }

    public function getBrowserScreenWidth(): ?string
    {
        return $this->browserScreenWidth;
    }

    /**
     * @return static
     */
    public function setBrowserScreenWidth(?string $browserScreenWidth)
    {
        $this->browserScreenWidth = $browserScreenWidth;

        return $this;
    }

    public function getBrowserTZ(): ?string
    {
        return $this->browserTZ;
    }

    /**
     * @return static
     */
    public function setBrowserTZ(?string $browserTZ)
    {
        $this->browserTZ = $browserTZ;

        return $this;
    }

    public function getBrowserUserAgent(): ?string
    {
        return $this->browserUserAgent;
    }

    /**
     * @return static
     */
    public function setBrowserUserAgent(?string $browserUserAgent)
    {
        $this->browserUserAgent = $browserUserAgent;

        return $this;
    }

    public function getChallengeWindowSize(): ?string
    {
        return $this->challengeWindowSize;
    }

    /**
     * @return static
     */
    public function setChallengeWindowSize(?string $challengeWindowSize)
    {
        $this->challengeWindowSize = $challengeWindowSize;

        return $this;
    }

    public function getAcctID(): ?string
    {
        return $this->acctID;
    }

    /**
     * @return static
     */
    public function setAcctID(?string $acctID)
    {
        $this->acctID = $acctID;

        return $this;
    }

    public function getTransType(): ?string
    {
        return $this->transType;
    }

    /**
     * @return static
     */
    public function setTransType(?string $transType)
    {
        $this->transType = $transType;

        return $this;
    }

    public function getThreeDSRequestorAuthenticationInfo(): ?ThreeDSRequestorAuthenticationInfoInterface
    {
        return $this->threeDSRequestorAuthenticationInfo;
    }

    /**
     * @return static
     */
    public function setThreeDSRequestorAuthenticationInfo(?ThreeDSRequestorAuthenticationInfoInterface $threeDSRequestorAuthenticationInfo)
    {
        $this->threeDSRequestorAuthenticationInfo = $threeDSRequestorAuthenticationInfo;

        return $this;
    }

    public function getThreeDSRequestorPriorAuthenticationInfo(): ?ThreeDSRequestorPriorAuthenticationInfoInterface
    {
        return $this->threeDSRequestorPriorAuthenticationInfo;
    }

    /**
     * @return static
     */
    public function setThreeDSRequestorPriorAuthenticationInfo(?ThreeDSRequestorPriorAuthenticationInfoInterface $threeDSRequestorPriorAuthenticationInfo)
    {
        $this->threeDSRequestorPriorAuthenticationInfo = $threeDSRequestorPriorAuthenticationInfo;

        return $this;
    }

    public function getAcctInfo(): ?AccountInfoInterface
    {
        return $this->acctInfo;
    }

    /**
     * @return static
     */
    public function setAcctInfo(?AccountInfoInterface $acctInfo)
    {
        $this->acctInfo = $acctInfo;

        return $this;
    }

    public function getMerchantRiskIndicator(): ?MerchantRiskIndicatorInterface
    {
        return $this->merchantRiskIndicator;
    }

    /**
     * @return static
     */
    public function setMerchantRiskIndicator(?MerchantRiskIndicatorInterface $merchantRiskIndicator)
    {
        $this->merchantRiskIndicator = $merchantRiskIndicator;

        return $this;
    }

    public function getThreeDSExemptionIndicator(): ?string
    {
        return $this->threeDSExemptionIndicator;
    }

    /**
     * @return static
     */
    public function setThreeDSExemptionIndicator(?string $threeDSExemptionIndicator)
    {
        $this->threeDSExemptionIndicator = $threeDSExemptionIndicator;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @return static
     */
    public function setWebsite(?string $website)
    {
        $this->website = $website;

        return $this;
    }
}
