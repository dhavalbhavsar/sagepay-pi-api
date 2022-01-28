# Laravel SagePayPi

SagePayPi

Insiperd by [sagepay-pi](https://gitlab.com/lumnn/sagepay-pi)

# Update

Issue with error resolve with response error.

# Opayo PI PHP Client

[![pipeline status](https://gitlab.com/lumnn/sagepay-pi/badges/master/pipeline.svg)](https://gitlab.com/lumnn/sagepay-pi/-/commits/master) 
[![coverage report](https://gitlab.com/lumnn/sagepay-pi/badges/master/coverage.svg)](https://gitlab.com/lumnn/sagepay-pi/-/commits/master)
![Licence MIT](https://img.shields.io/packagist/l/lumnn/sagepay-pi)
![PHPStan - Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)

> PHP client for Opayo PI (formerly Sagepay PI) payment API.

## Installation

`composer require lumnn/sagepay-pi`

You'll also need packages providing:

- `psr/http-message-implementation` (PSR7)
- `psr/http-factory-implementation` (PSR17)
- `psr/http-client-implementation` (PSR18)

## Before Using

Generally I'd recommend looking into `tests/` directory and [Opayo PI API Reference](https://developer-eu.elavon.com/docs/opayo/spec/api-reference-0).

This library aims to be as close as it's possible to API docs in terms of implementation.

Every interface used by this library has default implementation in same namespace.

## Methods Reference

API endpoints and their library methods:

### Obtain Merchant Session Key

**`POST /merchant-session-keys/`**  
`Lumnn\SagePayPi\SagePayPi::getMerchantSessionKey(): string`

Will return previously created key if it's not expired or used. Not neccessarily needed for anything as library handles the generation and authentication

### Validate MSK

**`GET /merchant-session-keys/{merchantSessionKey}`**  
```Lumnn\SagePayPi\SagePayPi::validateMerchantSessionKey(string $merchantSessionKey): bool```

Will return bool whether key is valid

### Create Card Identifier

**`POST /card-identifiers`**  
```Lumnn\SagePayPi\Card\Cards::createIdentifier(CardDetailsInterface $card): CardIdentifierInterface```

### Link Reusable Security Code

**`POST /card-identifiers/{cardIdentifier}/security-code`**  
```Lumnn\SagePayPi\Card\Cards::linkReusableSecurityCode(CardIdentifierInterface $cardIdentifier, string $securityCode): void```

### Create Transaction (Payment/Refund/Repeat)

**`POST /transactions`**  
```Lumnn\SagePayPi\Transacton\Transactions::create(TransactionRequestInterface $transaction, array $options = []): AbstractResponse```

_Depending on `$transaction` class this will either create payment, refund or repeat transaction_

- **`@param $transaction`** - can be either of following:
    - `Lumnn\SagePayPi\Transaction\PaymentRequestInterface`
    - `Lumnn\SagePayPi\Transaction\RefundRequestInterface`
    - `Lumnn\SagePayPi\Transaction\RepeatRequestInterface`

- **`@param $options['save_card']`** - _optional, `bool`, default: `false`_  
whether save card for future usages. Only when creating payments, not for refund or repeat transactions

- **`@return AbstractResponse`** - All possible respones are in `Lumnn\SagePayPi\Transaction\Resopnse` namespace and are created by `Lumnn\SagePayPi\Transaction\Response\ResponseFactory`

### Retrieve Transaction

**`GET /transactions/{transactionId}`**  
```Lumnn\SagePayPi\Transacton\Transactions::get(string $transactionId): AbstractResponse```

### Create 3D Secure Object (3DSv1)

**`POST /transactions/{transactionId}/3d-secure`**  
```Lumnn\SagePayPi\Transacton\Transactions::create3DSv1(string $transactionId, string $paRes): string```

Returns status string

### Create 3D Secure Challenge (3DSv2)

**`POST /transactions/{transactionId}/3d-secure-challenge`**  
```Lumnn\SagePayPi\Transacton\Transactions::create3DSChallenge(string $transactionId, string $cRes): PaymentResponse```

Returns PaymentResponse

### Create an Instruction

**`POST /transactions/{transactionId}/instructions`**  
_not implemented_

## Examples

### Constructing client and using resources

The `SagePayPi` class is a basic client that is able to perform authenticated requests to API.

It's also in charge of merchant session key and authenticating requests.

Generally, unless you want to speak directly with API it's only required for instantiating other resource classes.

```php
use Lumnn\SagePayPi\SagePayPi;

$sagepay = new SagePayPi(
    'vendor',
    'integration_key',
    'integration_password',
    SagePayPi::TEST_ENDPOINT // or SagePayPi::LIVE_ENDPOINT, or ommit for live one
);
```

After getting a client you can instantiate resource classes

```php
use Lumnn\SagePayPi\Card\Cards;
use Lumnn\SagePayPi\Transaction\Transactions;

$cards = new Cards($sagepay);
$transactions = new Transactions($sagepay);
```

### Creating Card Identifier

This example creates a credit card representation and POST-s that to Opayo for retrieving the card identifier.

This step can (should?) be done in customer browser to avoid deling with credit card details directly. Documentation: https://developer-eu.elavon.com/docs/opayo/integrate-your-own-form#step-3

```php
use Lumnn\SagePayPi\Card\CardDetails;
use Lumnn\SagePayPi\Card\Cards;

$cards = new Cards($sagepay);
$card = new CardDetails();
$card
    ->setCardNumber("1234123412341234")
    ->setCardholderName('Test Name')
    ->setExpiryDate('1223') // December 2023
    ->setSecurityCode('123');

$cardIdentifier = $cards->createIdentifier($card);
```

### Processing Telephone Payment (without 3D Secure)

```php
use Lumnn\SagePayPi\Transaction\PaymentRequest;
use Lumnn\SagePayPi\Transaction\Transactions;

// $sagepay - from previous example
// $cardIdentifier - from previous examples
// $address - just intantiated and populated Address class. It's a simple getter setter class

$transactions = new Transactions($sagepay);

$paymentRequest = new PaymentRequest();
$paymentRequest
    ->setAmount(1000)
    ->setCurrency('GBP')
    ->setDescription('One testing service of SagePay Pi PHP Library')
    ->setBillingDetails($address)
    ->setCardIdentifier($cardIdentifier)
    ->setCustomerEmail('test@example.org')
    ->setEntryMethod(PaymentRequestInterface::ENTRY_TELEPHONE_ORDER)
    ->setTransactionType(PaymentRequestInterface::TYPE_PAYMENT)
    ->setVendorTxCode('php_lib_test'.time());

$payment = $this->transactions->createPayment($paymentRequest);
```

### 3D Secure Payment

```php
use Lumnn\SagePayPi\Transaction\PaymentRequest;
use Lumnn\SagePayPi\Transaction\Transactions;
use Lumnn\SagePayPi\Transaction\Request\StrongCustomerAuthentication;
use Lumnn\SagePayPi\Transaction\Request\StrongCustomerAuthenticationInterface;
use Lumnn\SagePayPi\Transaction\Response\ThreeDSv2AuthResponse;

// $sagepay - from previous example
// $cardIdentifier - from previous examples
// $address - just intantiated and populated Address class. It's a simple getter setter class

$transactions = new Transactions($sagepay);

$paymentRequest = new PaymentRequest();
$paymentRequest
    ->setAmount(1000)
    ->setCurrency('GBP')
    ->setDescription('One testing service of SagePay Pi PHP Library')
    ->setBillingDetails($address)
    ->setCardIdentifier($cardIdentifier)
    ->setCustomerEmail('test@example.org')
    ->setEntryMethod(PaymentRequestInterface::ENTRY_ECOMMERCE)
    ->setApply3DSecure(PaymentRequestInterface::SECURITY_CHECK_FORCE)
    ->setVendorTxCode('php_lib_test_3dv2'.time());

$strongCustomerAuth = new StrongCustomerAuthentication();

$notificationURL = 'http://127.0.0.1:8080';

$strongCustomerAuth
    ->setNotificationURL($notificationURL)
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

$paymentRequest->setStrongCustomerAuthentication($strongCustomerAuth);

$threeDSv2AuthResponse = $this->transactions->createPayment($paymentRequest);

// for simplicity in this example. However it's not guaranteed it's going to be 3Ds v2 challenge
if (!$payment instanceof ThreeDSv2AuthResponse) {
    throw new \Exception("Response invalid in this example");
}

$acsUrl = $threeDSv2AuthResponse->getAcsUrl();
$acsParams = [
    'creq' => $threeDSv2AuthResponse->getCReq(),
];

// at this point you should redirect customer to acsUrl with creq param
// it will then come back with cRes value. The cRes value will be POST-ed to
// your $notificationURL

$cRes = $_POST['cRes'];

$completedPayment = $transactions->create3DSChallenge($threeDSv2AuthResponse->getId(), $cRes);
```
