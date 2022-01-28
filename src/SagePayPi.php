<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi;

use GuzzleHttp\Psr7\Utils;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Lumnn\SagePayPi\Exceptions\ClientException;
use Lumnn\SagePayPi\Exceptions\ServerException;
use Lumnn\SagePayPi\Exceptions\ValidationException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A representation of a SagePay PI (Opayo PI) integration.
 */
class SagePayPi
{
    public const LIVE_ENDPOINT = 'https://pi-live.sagepay.com/api/v1/';
    public const TEST_ENDPOINT = 'https://pi-test.sagepay.com/api/v1/';

    private string $endpoint;

    private string $vendorName;

    private string $integrationKey;

    private string $integrationPassword;

    private ?string $merchantSessionKey = null;

    private ?\DateTime $merchantSessionKeyExpiry = null;

    private ?int $merchantSessionKeyFails = 0;

    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    /**
     * Creates a new SagePay PI class with connection details.
     */
    public function __construct(string $vendorName, string $integrationKey, string $integrationPassword, ?string $endpoint = self::LIVE_ENDPOINT, ?ClientInterface $client = null, ?RequestFactoryInterface $requestFactory = null)
    {
        $this->vendorName = $vendorName;
        $this->integrationKey = $integrationKey;
        $this->integrationPassword = $integrationPassword;
        $this->endpoint = $endpoint ?: self::LIVE_ENDPOINT;

        $this->client = $client ?: Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
    }

    /**
     * Returns JSON decoded from response.
     *
     * @param ResponseInterface $response The guzzle response
     *
     * @throws \RuntimeException When Content-Type is not application/json
     */
    public static function decodeResponseBody(ResponseInterface $response): \stdClass
    {
        if ('application/json' !== $response->getHeaderLine('Content-Type')) {
            throw new \RuntimeException('Expected server response Content-Type to be application/json');
        }

        $decoded = json_decode((string) $response->getBody(), false, 32, JSON_THROW_ON_ERROR);

        if (!($decoded instanceof \stdClass)) {
            throw new \RuntimeException('Response was expected to be an object');
        }

        return $decoded;
    }

    /**
     * Gets the vendor name.
     *
     * @return string the vendor name
     */
    public function getVendorName(): string
    {
        return $this->vendorName;
    }

    /**
     * Gets the endpoint.
     *
     * @return string the endpoint
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Makes a Guzzle Request.
     *
     * @param array<mixed,mixed>   $data
     * @param array<string,string> $headers
     */
    public function jsonRequest(string $method, string $path, array $data = [], array $headers = []): ?\stdClass
    {
        $path = ltrim($path, '/');
        $uri = $this->endpoint.$path;

        $headers = array_merge([
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'application/json',
        ], $headers);

        if (!isset($headers['Authorization']) || 'Basic' === $headers['Authorization']) {
            // no authorization header adds default key/password auth
            $headers['Authorization'] = $this->createHttpAuthHeaderValue();
        } elseif ('Bearer' === $headers['Authorization']) {
            // Bearer auth header appended with merchant session key
            $headers['Authorization'] .= ' '.$this->getMerchantSessionKey();
        }
        $jsonData = json_encode($data, JSON_THROW_ON_ERROR);

        $request = $this->requestFactory->createRequest($method, $uri);
        $request = $request->withBody(Utils::streamFor($jsonData));
        foreach ($headers as $key => $value) {
            $request = $request->withAddedHeader($key, $value);
        }

        $response = $this->client->sendRequest($request);

        $isBearer = $this->merchantSessionKey && false !== strpos($request->getHeaderLine('Authorization'), $this->merchantSessionKey);
        $statusCode = $response->getStatusCode();

        // merchant session key successfully used
        if ($isBearer && $statusCode >= 200 && $statusCode < 400) {
            $this->merchantSessionKey = null;
        }

        // no content successfull response
        if (204 === $statusCode) {
            return null;
        }

        $body = null;

        try {
            $body = self::decodeResponseBody($response);
        } catch (\Exception $e) {
        }

        // content successfull response
        if (in_array($statusCode, [200, 201, 202])) {
            return $body;
        }

        // unsuccessfull response counted for merchant session key fails
        if ($isBearer) {
            ++$this->merchantSessionKeyFails;
        }

        if ($statusCode >= 500) {
            throw new ServerException($request, $response, $body);
        }

        if (422 === $statusCode && isset($body->errors) && count($body->errors) > 0 && isset($body->errors[0]->property)) {
            throw new ValidationException($request, $response, $body);
        }

        throw new ClientException($request, $response, $body);
    }

    /**
     * Creates a merchant session key.
     */
    protected function createMerchantSessionKey(): string
    {
        $response = $this->jsonRequest('POST', 'merchant-session-keys', [
            'vendorName' => $this->vendorName,
        ]);

        if (!$response) {
            throw new \RuntimeException('Expected not null response data');
        }

        $this->merchantSessionKey = $response->merchantSessionKey;
        $this->merchantSessionKeyExpiry = new \DateTime($response->expiry);
        $this->merchantSessionKeyFails = 0;

        return $this->merchantSessionKey;
    }

    /**
     * Gets the merchant session key.
     *
     * @return string the merchant session key
     */
    public function getMerchantSessionKey(): string
    {
        if ($this->merchantSessionKey &&
            $this->merchantSessionKeyExpiry &&
            $this->merchantSessionKeyExpiry > new \DateTime() &&
            $this->merchantSessionKeyFails < 3
        ) {
            return $this->merchantSessionKey;
        }

        return $this->createMerchantSessionKey();
    }

    /**
     * Gets the merchant session key expiry.
     *
     * @return \DateTime|null the merchant session key expiry
     */
    public function getMerchantSessionKeyExpiry(): ?\DateTime
    {
        return $this->merchantSessionKeyExpiry;
    }

    /**
     * Validates the merchant session key. Returns true if valid.
     *
     * @param string $merchantSessionKey The merchant session key
     *
     * @return bool|bool True if valid, false otherwise
     */
    public function validateMerchantSessionKey(string $merchantSessionKey): bool
    {
        try {
            $this->jsonRequest('GET', 'merchant-session-keys/'.$merchantSessionKey);

            return true;
        } catch (ClientException $exception) {
            return false;
        }
    }

    private function createHttpAuthHeaderValue(): string
    {
        return 'Basic '.base64_encode($this->integrationKey.':'.$this->integrationPassword);
    }
}
