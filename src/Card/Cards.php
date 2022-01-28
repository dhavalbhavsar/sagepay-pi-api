<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Card;

use Lumnn\SagePayPi\SagePayPi;

/**
 * Class handling Cards resources on sagepay pi api.
 */
class Cards
{
    private SagePayPi $sagePay;

    public function __construct(SagePayPi $sagePay)
    {
        $this->sagePay = $sagePay;
    }

    /**
     * Creates a card identifier, to be used later for transaction.
     */
    public function createIdentifier(CardDetailsInterface $cardDetails): CardIdentifierInterface
    {
        $sessionKey = $this->sagePay->getMerchantSessionKey();

        $response = $this->bearerRequest('POST', 'card-identifiers', [
            'cardDetails' => CardDetails::toArray($cardDetails),
        ]);

        if (!$response) {
            throw new \RuntimeException('The response had no content. Expected it!');
        }

        $cardId = new CardIdentifier(
            $response->cardIdentifier,
            new \DateTime($response->expiry)
        );

        $cardId
            ->setMerchantSessionKey($sessionKey)
            ->setCardType($response->cardType);

        return $cardId;
    }

    /**
     * Sets the security code for reusable card to be used again in transaction.
     *
     * @throws \InvalidArgumentException When card is not reusable
     */
    public function linkReusableSecurityCode(CardIdentifierInterface $cardIdentifier, string $securityCode): void
    {
        if (!$cardIdentifier->isReusable()) {
            throw new \InvalidArgumentException('Card is not reusable');
        }

        $this->bearerRequest(
            'POST',
            'card-identifiers/'.$cardIdentifier->getId().'/security-code',
            ['securityCode' => $securityCode]
        );
    }

    /**
     * Makes a Bearer auth request used for card endpoint rather than Basic authentication.
     *
     * @param array<string, mixed> $data
     */
    protected function bearerRequest(string $method, string $uri, array $data = []): ?\stdClass
    {
        return $this->sagePay->jsonRequest(
            $method,
            $uri,
            $data,
            [
                'Authorization' => 'Bearer',
            ],
        );
    }
}
