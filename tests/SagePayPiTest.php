<?php

namespace Lumnn\SagePayPi\Tests;

use Lumnn\SagePayPi\SagePayPi;
use PHPUnit\Framework\TestCase;

final class SagePayPiTest extends TestCase
{
    public const TEST_VENDOR = 'sandbox';

    public const TEST_INTEGRATION_KEY = 'hJYxsw7HLbj40cB8udES8CDRFLhuJ8G54O6rDpUXvE6hYDrria';

    public const TEST_INTEGRATION_PASSWORD = 'o2iHSrFybYMZpmWOQMuhsXP52V4fBtpuSDshrKDSWsBY1OiN6hwd9Kb12z4j5Us5u';

    public static function createLiveTestInstance(): SagePayPi
    {
        return new SagePayPi(
            self::TEST_VENDOR,
            self::TEST_INTEGRATION_KEY,
            self::TEST_INTEGRATION_PASSWORD,
            SagePayPi::TEST_ENDPOINT
        );
    }

    public function testConstruct(): SagePayPi
    {
        $api = self::createLiveTestInstance();

        $this->assertEquals(
            SagePayPi::TEST_ENDPOINT,
            $api->getEndpoint(),
            'Endpoint must be taken from fourth parameter'
        );

        return $api;
    }

    public function apiKeysProvider(): array
    {
        return [
            ['test', 'test', 'test', null],
            ['ASD!@#1233', '---', '---', null],
            ['ASD!@#1233', '', '', ''],
        ];
    }

    /**
     * @dataProvider apiKeysProvider
     */
    public function testDefaultEndpoint(string $vendorName, string $integrationKey, string $integrationPassword, $endpoint = null)
    {
        if (null === $endpoint) {
            $integration = new SagePayPi($vendorName, $integrationKey, $integrationPassword);
        } else {
            $integration = new SagePayPi($vendorName, $integrationKey, $integrationPassword, $endpoint);
        }

        $this->assertEquals(
            $endpoint ? $endpoint : SagePayPi::LIVE_ENDPOINT,
            $integration->getEndpoint(),
            'getEndpoint() must return passed endpoint in construct, if empty then SagePayPi::LIVE_ENDPOINT'
        );
    }

    /**
     * @depends testConstruct
     */
    public function testCreateMerchantSessionKey(SagePayPi $api): string
    {
        $sessionKey = $api->getMerchantSessionKey();

        $this->assertEquals(
            $sessionKey,
            $api->getMerchantSessionKey(),
            'Merchant Session Key generated through getMerchantSessionKey() must be the same for 400 seconds after calling the first one'
        );

        return $sessionKey;
    }

    /**
     * @depends testConstruct
     * @depends testCreateMerchantSessionKey
     */
    public function testGetMerchantSessionKey(SagePayPi $api, string $oldSessionKey)
    {
        // we manually update merchantSessionKeyExpiry to make sure that it's expired
        // So we won't really have to wait 400 seconds
        $reflection = new \ReflectionClass($api);
        $property = $reflection->getProperty('merchantSessionKeyExpiry');
        $property->setAccessible(true);
        $property->setValue($api, new \DateTime());

        $sessionKey = $api->getMerchantSessionKey();

        $this->assertNotEquals(
            $oldSessionKey,
            $sessionKey,
            'Session keys got from getMerchantSessionKey() must be different after 400 seconds from calling createMerchantSessionKey()'
        );
    }

    /**
     * @depends testConstruct
     * @depends testCreateMerchantSessionKey
     */
    public function testValidateMerchantSessionKey(SagePayPi $api, string $sessionKey)
    {
        $this->assertTrue($api->validateMerchantSessionKey($sessionKey));
        $this->assertFalse($api->validateMerchantSessionKey('WRONGMERCHANTSESSIONKEY'));
    }
}
