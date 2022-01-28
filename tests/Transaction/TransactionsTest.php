<?php

namespace Lumnn\SagePayPi\Tests\Transaction;

use GuzzleHttp\Client;
use Lumnn\SagePayPi\Address;
use Lumnn\SagePayPi\AddressInterface;
use Lumnn\SagePayPi\Card\CardDetails;
use Lumnn\SagePayPi\Card\CardIdentifierInterface;
use Lumnn\SagePayPi\Card\Cards;
use Lumnn\SagePayPi\SagePayPi;
use Lumnn\SagePayPi\Tests\SagePayPiTest;
use Lumnn\SagePayPi\Transaction\PaymentRequest;
use Lumnn\SagePayPi\Transaction\PaymentRequestInterface;
use Lumnn\SagePayPi\Transaction\Request\StrongCustomerAuthentication;
use Lumnn\SagePayPi\Transaction\Request\StrongCustomerAuthenticationInterface;
use Lumnn\SagePayPi\Transaction\Response\PaymentResponse;
use Lumnn\SagePayPi\Transaction\Response\ThreeDSv1AuthResponse;
use Lumnn\SagePayPi\Transaction\Response\ThreeDSv2AuthResponse;
use Lumnn\SagePayPi\Transaction\Transactions;
use PHPUnit\Framework\TestCase;
use function preg_match;

final class TransactionsTest extends TestCase
{
    // test card number obtained from Api Reference
    public const TEST_CARD_NUMBER = '4917300000000008';

    protected SagePayPi $sagepayPi;

    protected Cards $cards;

    protected Transactions $transactions;

    protected function setUp(): void
    {
        $this->sagepayPi = SagePayPiTest::createLiveTestInstance();
        $this->cards = new Cards($this->sagepayPi);
        $this->transactions = new Transactions($this->sagepayPi);
    }

    public function testTelephonePayment(): void
    {
        $card = $this->createTestCard();

        $paymentRequest = $this->createBasicPaymentRequest()
            ->setEntryMethod(PaymentRequestInterface::ENTRY_TELEPHONE_ORDER)
            ->setTransactionType(PaymentRequestInterface::TYPE_PAYMENT)
            ->setVendorTxCode('php_lib_test'.time())
        ;

        $payment = $this->transactions->createPayment($paymentRequest);

        $this->assertInstanceOf(PaymentResponse::class, $payment);
        $this->assertSame($payment->getStatusCode(), '0000');
        $this->assertSame($payment->getStatus(), 'Ok');
    }

    public function test3DSv1Payment(): void
    {
        $card = $this->createTestCard();

        $paymentRequest = $this->createBasicPaymentRequest()
            ->setEntryMethod(PaymentRequestInterface::ENTRY_ECOMMERCE)
            ->setApply3DSecure(PaymentRequestInterface::SECURITY_CHECK_FORCE)
            ->setVendorTxCode('php_lib_test_3d'.time())
        ;

        $payment = $this->transactions->createPayment($paymentRequest);

        $this->assertInstanceOf(ThreeDSv1AuthResponse::class, $payment);
        $this->assertSame($payment->getStatusCode(), '2007');
        $this->assertSame($payment->getStatus(), '3DAuth');
    }

    public function testCreate3DSv2Payment(): ThreeDSV2AuthResponse
    {
        $card = $this->createTestCard();

        $paymentRequest = $this->createBasicPaymentRequest()
            ->setEntryMethod(PaymentRequestInterface::ENTRY_ECOMMERCE)
            ->setApply3DSecure(PaymentRequestInterface::SECURITY_CHECK_FORCE)
            ->setVendorTxCode('php_lib_test_3dv2'.time())
            ->setStrongCustomerAuthentication($this->createStrongCustomerAuthentication())
        ;

        $payment = $this->transactions->createPayment($paymentRequest);

        $this->assertInstanceOf(ThreeDSv2AuthResponse::class, $payment);
        $this->assertSame($payment->getStatusCode(), '2021');
        $this->assertSame($payment->getStatus(), '3DAuth');

        return $payment;
    }

    /**
     * @depends testCreate3DSv2Payment
     */
    public function testCreate3DSChallenge(ThreeDSv2AuthResponse $threeDSv2AuthResponse): void
    {
        $acsUrl = $threeDSv2AuthResponse->getAcsUrl();
        $acsParams = [
            'creq' => $threeDSv2AuthResponse->getCReq(),
        ];

        $client = new Client();
        $response = $client->request('POST', $acsUrl, [
            'form_params' => $acsParams,
            'allow_redirects' => true,
        ]);

        $challengeAnswer = (string) $response->getBody();

        // For some reason when testing manually I get directly the redirect back to acsUrl,
        // while the PHPUnit tests gets this html_challenge_answer form.
        // In that case I simulate here sending a challenge response
        if (-1 !== strpos($challengeAnswer, '/3ds-simulator/html_challenge_answer')) {
            preg_match('@acsTransactionID" value="([^"]+)"@', $challengeAnswer, $matches);
            $acsTransactionId = $matches[1];

            $challengeResponse = $client->request('POST', 'https://test.sagepay.com/3ds-simulator/html_challenge_answer', [
                'form_params' => [
                    'acsTransactionID' => $acsTransactionId,
                    'challengeData' => 'challenge',
                ],
                'allow_redirects' => true,
            ]);

            $challengeAnswer = (string) $challengeResponse->getBody();
        }

        if (!preg_match('@cres" value="([^"]+)"@', $challengeAnswer, $matches)) {
            throw new \Exception("Couldn't find cRes value");
        }

        $cRes = $matches[1];

        $paymentResponse = $this->transactions->create3DSChallenge($threeDSv2AuthResponse->getId(), $cRes);

        $this->assertInstanceOf(PaymentResponse::class, $paymentResponse);
        $this->assertSame($paymentResponse->getStatusCode(), '0000');
        $this->assertSame($paymentResponse->getStatus(), 'Ok');
        $this->assertSame($paymentResponse->getId(), $threeDSv2AuthResponse->getId());
        $this->assertSame($paymentResponse->get3dSecureStatus(), 'Authenticated');
    }

    protected function createTestCard(): CardIdentifierInterface
    {
        $card = new CardDetails();
        $card
            ->setCardNumber(self::TEST_CARD_NUMBER)
            ->setCardholderName('Test Name')
            ->setExpiryDate('12'.(date('y') + 1))
            ->setSecurityCode('123');

        return $this->cards->createIdentifier($card);
    }

    protected function createAddress(): AddressInterface
    {
        $address = new Address();

        $address
            ->setFirstName('John')
            ->setLastName('Price')
            ->setAddress1('Testing street')
            ->setAddress2('Unit 4')
            ->setCity('Testingham')
            ->setPostalCode('TE1 2ST')
            ->setCountry('GB');

        return $address;
    }

    protected function createBasicPaymentRequest(): PaymentRequest
    {
        $paymentRequest = new PaymentRequest();
        $paymentRequest
            ->setAmount(1000)
            ->setCurrency('GBP')
            ->setDescription('One testing service of SagePay Pi PHP Library')
            ->setBillingDetails($this->createAddress())
            ->setCardIdentifier($this->createTestCard())
            ->setCustomerEmail('test@example.org')
            ->setTransactionType(PaymentRequestInterface::TYPE_PAYMENT);

        return $paymentRequest;
    }

    protected function createStrongCustomerAuthentication(): StrongCustomerAuthentication
    {
        $auth = new StrongCustomerAuthentication();

        return $auth
            ->setNotificationURL('http://127.0.0.1:8080')
            ->setBrowserIP('127.0.0.1')
            ->setBrowserAcceptHeader('*/*')
            ->setBrowserJavascriptEnabled(true)
            ->setBrowserJavaEnabled(false)
            ->setBrowserLanguage('en-US')
            ->setBrowserColorDepth('32')
            ->setBrowserScreenHeight('1080')
            ->setBrowserScreenWidth('1920')
            ->setBrowserTZ('0')
            ->setBrowserUserAgent('Mozilla/5.0 (Linux x86_64; rv:93.0) Gecko/20100101 Firefox/93.0')
            ->setChallengeWindowSize('Small')
            ->setTransType(StrongCustomerAuthenticationInterface::TRANSACTION_TYPE_GOODS_AND_SERVICE_PURCHASE);
    }
}
