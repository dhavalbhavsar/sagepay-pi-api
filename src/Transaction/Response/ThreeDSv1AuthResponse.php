<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction\Response;

/**
 * Class containing sagepay transaction response, that instructs to redirect
 * customer to a page to finish 3d secure authentication.
 */
class ThreeDSv1AuthResponse extends AbstractResponse
{
    public function getAcsUrl(): string
    {
        return $this->getResponseData()->acsUrl;
    }

    public function getPaReq(): string
    {
        return $this->getResponseData()->paReq;
    }
}
