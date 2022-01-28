<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Request;

interface ThreeDSRequestorPriorAuthenticationInfoInterface
{
    public const METHOD_FRICTIONLESS_AUTHENTICATION = 'FrictionlessAuthentication';
    public const METHOD_CHALLENGE_AUTHENTICATION = 'ChallengeAuthentication';
    public const METHOD_AVS_VERIFIED = 'AVSVerified';
    public const METHOD_OTHER_ISSUER_METHODS = 'OtherIssuerMethods';

    public function getThreeDSReqPriorAuthData(): ?string;

    public function getThreeDSReqPriorAuthMethod(): ?string;

    public function getThreeDSReqPriorAuthTime(): ?\DateTimeInterface;

    public function getThreeDSReqPriorRef(): ?string;
}
