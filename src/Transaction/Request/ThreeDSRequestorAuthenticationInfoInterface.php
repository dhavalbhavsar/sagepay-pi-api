<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

interface ThreeDSRequestorAuthenticationInfoInterface
{
    public const METHOD_NO_THREEDS_REQUESTOR_AUTHENTICATION = 'NoThreeDSRequestorAuthentication';
    public const METHOD_LOGIN_WITH_THREEDS_REQUESTOR_CREDENTIALS = 'LoginWithThreeDSRequestorCredentials';
    public const METHOD_LOGIN_WITH_FEDERATED_ID = 'LoginWithFederatedId';
    public const METHOD_LOGIN_WITH_ISSUER_CREDENTIALS = 'LoginWithIssuerCredentials';
    public const METHOD_LOGIN_WITH_THIRD_PARTY_AUTHENTICATION = 'LoginWithThirdPartyAuthentication';
    public const METHOD_LOGIN_WITH_FIDO_AUTHENTICATOR = 'LoginWithFIDOAuthenticator';

    public function getThreeDSReqAuthData(): ?string;

    public function getThreeDSReqAuthMethod(): ?string;

    public function getThreeDSReqAuthTime(): ?\DateTimeInterface;
}
