<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

interface StrongCustomerAuthenticationInterface
{
    public const CHALLENGE_WINDOW_SIZE_SMALL = 'Small';
    public const CHALLENGE_WINDOW_SIZE_MEDIUM = 'Medium';
    public const CHALLENGE_WINDOW_SIZE_LARGE = 'Large';
    public const CHALLENGE_WINDOW_SIZE_EXTRA_LARGE = 'ExtraLarge';
    public const CHALLENGE_WINDOW_SIZE_FULL_SCREEN = 'FullScreen';

    public const TRANSACTION_TYPE_GOODS_AND_SERVICE_PURCHASE = 'GoodsAndServicePurchase';
    public const TRANSACTION_TYPE_CHECK_ACCEPTANCE = 'CheckAcceptance';
    public const TRANSACTION_TYPE_ACCOUNT_FUNDING = 'AccountFunding';
    public const TRANSACTION_TYPE_QUASI_CASH_TRANSACTION = 'QuasiCashTransaction';
    public const TRANSACTION_TYPE_PREPAID_ACTIVATION_AND_LOAD = 'PrepaidActivationAndLoad';

    public const THREEDS_EXEMPTION_INDICATOR_LOW_VALUE = 'LowValue';
    public const THREEDS_EXEMPTION_INDICATOR_TRANSACTION_RISK_ANALYSIS = 'TransactionRiskAnalysis';
    public const THREEDS_EXEMPTION_INDICATOR_TRUSTED_MERCHANT = 'TrustedMerchant';
    public const THREEDS_EXEMPTION_INDICATOR_SECURE_CORPORATE_PAYMENT = 'SecureCorporatePayment';
    public const THREEDS_EXEMPTION_INDICATOR_DELEGATED_AUTHENTICATION = 'DelegatedAuthentication';

    public function getNotificationURL(): ?string;

    public function getBrowserIP(): ?string;

    public function getBrowserAcceptHeader(): ?string;

    public function getBrowserJavascriptEnabled(): ?bool;

    public function getBrowserJavaEnabled(): ?bool;

    public function getBrowserLanguage(): ?string;

    public function getBrowserColorDepth(): ?string;

    public function getBrowserScreenHeight(): ?string;

    public function getBrowserScreenWidth(): ?string;

    public function getBrowserTZ(): ?string;

    public function getBrowserUserAgent(): ?string;

    public function getChallengeWindowSize(): ?string;

    public function getAcctID(): ?string;

    public function getTransType(): ?string;

    public function getThreeDSRequestorAuthenticationInfo(): ?ThreeDSRequestorAuthenticationInfoInterface;

    public function getThreeDSRequestorPriorAuthenticationInfo(): ?ThreeDSRequestorPriorAuthenticationInfoInterface;

    public function getAcctInfo(): ?AccountInfoInterface;

    public function getMerchantRiskIndicator(): ?MerchantRiskIndicatorInterface;

    public function getThreeDSExemptionIndicator(): ?string;

    public function getWebsite(): ?string;
}
