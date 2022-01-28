<?php

/*
 * This file is part of the SagePayPi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lumnn\SagePayPi\Transaction;

use Lumnn\SagePayPi\AddressInterface;
use Lumnn\SagePayPi\Card\CardIdentifierInterface;
use Lumnn\SagePayPi\SagePayPi;
use Lumnn\SagePayPi\Transaction\Request\AccountInfoInterface;
use Lumnn\SagePayPi\Transaction\Request\MerchantRiskIndicatorInterface;
use Lumnn\SagePayPi\Transaction\Request\StrongCustomerAuthenticationInterface;
use Lumnn\SagePayPi\Transaction\Request\ThreeDSRequestorAuthenticationInfoInterface;
use Lumnn\SagePayPi\Transaction\Request\ThreeDSRequestorPriorAuthenticationInfoInterface;
use Lumnn\SagePayPi\Transaction\Response\AbstractResponse;
use Lumnn\SagePayPi\Transaction\Response\PaymentResponse;
use Lumnn\SagePayPi\Transaction\Response\Response;
use Lumnn\SagePayPi\Transaction\Response\ResponseFactory;

/**
 * A class handling transaction resources.
 */
class Transactions
{
    /**
     * @var SagePayPi
     */
    private $sagePay;

    /**
     * @var ResponseFactory
     */
    private $transactionResponseFactory;

    /**
     * Creates the Transaction class.
     *
     * @param SagePayPi $sagePay The SagePay Pi class
     */
    public function __construct(SagePayPi $sagePay)
    {
        $this->sagePay = $sagePay;
        $this->transactionResponseFactory = new ResponseFactory();
    }

    public function get(string $transactionId): AbstractResponse
    {
        $transactionData = $this->sagePay->jsonRequest('GET', "transactions/${transactionId}");

        if (!$transactionData) {
            throw new \Exception('Unexpected empty data received');
        }

        return $this->transactionResponseFactory->createResponse($transactionData);
    }

    /**
     * Creates a transaction.
     *
     * @param array{'save_card'?: bool} $options
     *
     * @throws \InvalidArgumentException When card identifier is expired or when transaction class is not supported
     */
    public function create(TransactionRequestInterface $transaction, array $options = []): AbstractResponse
    {
        if ($transaction instanceof PaymentRequestInterface) {
            return $this->createPayment($transaction, $options);
        }

        if ($transaction instanceof RefundRequestInterface) {
            return $this->createRefund($transaction);
        }

        if ($transaction instanceof RepeatRequestInterface) {
            return $this->createRepeat($transaction);
        }

        throw new \InvalidArgumentException('Passed Transaction is not supported');
    }

    /**
     * Creates a payment transaction.
     *
     * @param array{'save_card'?: bool} $options
     */
    public function createPayment(PaymentRequestInterface $transaction, array $options = []): AbstractResponse
    {
        $cardIdentifier = $transaction->getCardIdentifier();

        if (!$cardIdentifier) {
            throw new \InvalidArgumentException('Missing card identifier');
        }

        $billingAddress = $transaction->getBillingDetails();

        if (!$billingAddress) {
            throw new \InvalidArgumentException('Missing billing details');
        }

        if ($cardIdentifier->isIdentifierExpired()) {
            throw new \InvalidArgumentException('Card identifier is expired');
        }

        $data = [
            'transactionType' => $transaction->getTransactionType(),
            'paymentMethod' => [
                'card' => [
                    'merchantSessionKey' => $cardIdentifier->getMerchantSessionKey(),
                    'cardIdentifier' => $cardIdentifier->getId(),
                    // save and reusable to be added conditionally
                ],
            ],
            'vendorTxCode' => $transaction->getVendorTxCode(),
            'amount' => $transaction->getAmount(),
            'currency' => $transaction->getCurrency(),
            'description' => $transaction->getDescription(),
            'customerFirstName' => $billingAddress->getFirstName(),
            'customerLastName' => $billingAddress->getLastName(),
            'billingAddress' => self::addressToBillingArray($billingAddress),
        ];

        if ($cardIdentifier->isReusable()) {
            $data['paymentMethod']['card']['reusable'] = true;
        } elseif (true === ($options['save_card'] ?? false)) {
            $data['paymentMethod']['card']['save'] = true;
        }

        if (PaymentRequestInterface::ENTRY_ECOMMERCE !== $transaction->getEntryMethod()) {
            $data['entryMethod'] = $transaction->getEntryMethod();
        }

        if ($transaction->getGiftAid()) {
            $data['giftAid'] = true;
        }

        if (PaymentRequestInterface::SECURITY_CHECK_DEFAULT !== $transaction->getApply3DSecure()) {
            $data['apply3DSecure'] = $transaction->getApply3DSecure();
        }

        if (PaymentRequestInterface::SECURITY_CHECK_DEFAULT !== $transaction->getApplyAvsCvcCheck()) {
            $data['applyAvsCvcCheck '] = $transaction->getApplyAvsCvcCheck();
        }

        if ($transaction->getCustomerEmail()) {
            $data['customerEmail'] = $transaction->getCustomerEmail();
        }

        if ($transaction->getCustomerPhone()) {
            $data['customerPhone'] = $transaction->getCustomerPhone();
        }

        if ($shipping = $transaction->getShippingDetails()) {
            $data['shippingAddress'] = self::addressToShippingArray($shipping);
        }

        if ($strongCustomerAuthentication = $transaction->getStrongCustomerAuthentication()) {
            $data['strongCustomerAuthentication'] = self::strongCustomerAuthenticationToArray($strongCustomerAuthentication);
        }

        $responseContent = $this->sagePay->jsonRequest('POST', 'transactions', $data);

        if (!$responseContent) {
            throw new \RuntimeException('Expected not null response data');
        }

        $response = $this->transactionResponseFactory->createResponse($responseContent);

        $this->populateCardIdentifierFromResponse($responseContent, $cardIdentifier);

        return $response;
    }

    /**
     * Creates a refund.
     */
    public function createRefund(RefundRequestInterface $refundRequest): Response
    {
        $data = [
            'transactionType' => RefundRequestInterface::TYPE_REFUND,
            'referenceTransactionId' => $refundRequest->getReferenceTransactionId(),
            'vendorTxCode' => $refundRequest->getVendorTxCode(),
            'amount' => $refundRequest->getAmount(),
            'description' => $refundRequest->getDescription(),
        ];

        $responseContent = $this->sagePay->jsonRequest('POST', 'transactions', $data);

        if (!$responseContent) {
            throw new \RuntimeException('Expected not null response data');
        }

        $response = $this->transactionResponseFactory->createRefundResponse($responseContent);

        return $response;
    }

    public function createRepeat(RepeatRequestInterface $repeatRequest): Response
    {
        $data = [
            'transactionType' => RefundRequestInterface::TYPE_REFUND,
            'referenceTransactionId' => $repeatRequest->getReferenceTransactionId(),
            'vendorTxCode' => $repeatRequest->getVendorTxCode(),
            'amount' => $repeatRequest->getAmount(),
            'currency' => $repeatRequest->getCurrency(),
            'description' => $repeatRequest->getDescription(),
        ];

        if ($repeatRequest->getGiftAid()) {
            $data['giftAid'] = true;
        }

        if ($shipping = $repeatRequest->getShippingDetails()) {
            $data['shippingAddress'] = self::addressToShippingArray($shipping);
        }

        $responseContent = $this->sagePay->jsonRequest('POST', 'transactions', $data);

        if (!$responseContent) {
            throw new \RuntimeException('Expected not null response data');
        }

        $response = $this->transactionResponseFactory->createRepeatResponse($responseContent);

        return $response;
    }

    /**
     * Creates a 3D Secure Challenge. According to Opayo documentation I understand it in a way it confirms a payment.
     * In other words it authenticates a payment transaction with the code returned after user completed 3d secure v2
     * challenge.
     *
     * @param string $transactionId Id of the transaction to create a 3d secure challenge
     * @param string $cRes          cRes value from POST data sent to notification url after a successfull
     *                              3D challenge made on the acsUrl page
     *
     * @return PaymentResponse Completed payment object
     */
    public function create3DSChallenge(string $transactionId, string $cRes): PaymentResponse
    {
        $responseContent = $this->sagePay->jsonRequest('POST', "transactions/${transactionId}/3d-secure-challenge", [
            'cRes' => $cRes,
        ]);

        if (!$responseContent) {
            throw new \RuntimeException('Expected not null response data');
        }

        return $this->transactionResponseFactory->createPaymentResponse($responseContent);
    }

    /**
     * Creates a 3-D secure object for 3D Secure v1 challenge response. In other words it authenticates a payment
     * transaction with the code returned after user completed 3d secure v1 challenge.
     *
     * @param string $transactionId The transaction identifier
     * @param string $paRes         paRes value returned from Opayo after completing 3D secure v1 challenge
     *
     * @return string authentication status
     */
    public function create3DSv1(string $transactionId, string $paRes): string
    {
        $responseContent = $this->sagePay->jsonRequest('POST', "transactions/${transactionId}/3d-secure", [
            'paRes' => $paRes,
        ]);

        if (!$responseContent || !isset($responseContent->status)) {
            throw new \RuntimeException('Expected not null response data, with `status` value set');
        }

        return $responseContent->status;
    }

    /**
     * Updates passed card with data returned from server.
     *
     * @param \stdClass               $responseContent The server response
     * @param CardIdentifierInterface $cardIdentifier  The card identifier
     *                                                 class
     */
    protected function populateCardIdentifierFromResponse(\stdClass $responseContent, CardIdentifierInterface $cardIdentifier): void
    {
        if ('0000' !== $responseContent->statusCode ||
            !isset($responseContent->paymentMethod) ||
            !isset($responseContent->paymentMethod->card)
        ) {
            return;
        }

        $card = $responseContent->paymentMethod->card;

        $cardIdentifier
            ->setLastFourDigits($card->lastFourDigits)
            ->setExpiryDate($card->expiryDate);

        if (isset($card->reusable)) {
            $cardIdentifier->setReusable($card->reusable);
        }
    }

    /**
     * Converts the AddressInterface into SagePay billing address array.
     *
     * @return array<string,?string>
     */
    protected static function addressToBillingArray(AddressInterface $address): array
    {
        $array = [
            'address1' => $address->getAddress1(),
            'city' => $address->getCity(),
            'country' => $address->getCountry(),
        ];

        if ($address->getAddress2()) {
            $array['address2'] = $address->getAddress2();
        }

        if ($address->getPostalCode()) {
            $array['postalCode'] = $address->getPostalCode();
        }

        if ($address->getState()) {
            $array['state'] = $address->getState();
        }

        return $array;
    }

    /**
     * Converts the AddressInterface into SagePay shipping address array.
     *
     * @return array<string,?string>
     */
    protected static function addressToShippingArray(AddressInterface $address): array
    {
        $array = [
            'recipientFirstName' => $address->getFirstName(),
            'recipientLastName ' => $address->getLastName(),
            'shippingAddress1' => $address->getAddress1(),
            'shippingCity' => $address->getCity(),
            'shippingCountry' => $address->getCountry(),
        ];

        if ($address->getAddress2()) {
            $array['shippingAddress2'] = $address->getAddress2();
        }

        if ($address->getPostalCode()) {
            $array['shippingPostalCode'] = $address->getPostalCode();
        }

        if ($address->getState()) {
            $array['shippingState'] = $address->getState();
        }

        return $array;
    }

    /**
     * @return array<string,mixed>
     */
    public static function strongCustomerAuthenticationToArray(StrongCustomerAuthenticationInterface $strongAuth): array
    {
        $array = [
            'notificationURL' => $strongAuth->getNotificationURL(),
            'browserIP' => $strongAuth->getBrowserIP(),
            'browserAcceptHeader' => $strongAuth->getBrowserAcceptHeader(),
            'browserJavascriptEnabled' => $strongAuth->getBrowserJavascriptEnabled(),
            'browserJavaEnabled' => $strongAuth->getBrowserJavaEnabled(),
            'browserLanguage' => $strongAuth->getBrowserLanguage(),
            'browserUserAgent' => $strongAuth->getBrowserUserAgent(),
            'challengeWindowSize' => $strongAuth->getChallengeWindowSize(),
            'transType' => $strongAuth->getTransType(),
            'browserTZ' => $strongAuth->getBrowserTZ(),
        ];

        if ($browserColorDepth = $strongAuth->getBrowserColorDepth()) {
            $array['browserColorDepth'] = $browserColorDepth;
        }

        if ($browserScreenHeight = $strongAuth->getBrowserScreenHeight()) {
            $array['browserScreenHeight'] = $browserScreenHeight;
        }

        if ($browserScreenWidth = $strongAuth->getBrowserScreenWidth()) {
            $array['browserScreenWidth'] = $browserScreenWidth;
        }

        if ($acctID = $strongAuth->getAcctID()) {
            $array['acctID'] = $acctID;
        }

        if ($threeDSRequestorAuthenticationInfo = $strongAuth->getThreeDSRequestorAuthenticationInfo()) {
            $array['threeDSRequestorAuthenticationInfo'] = self::threeDSRequestorAuthenticationInfoToArray($threeDSRequestorAuthenticationInfo);
        }

        if ($threeDSRequestorPriorAuthenticationInfo = $strongAuth->getThreeDSRequestorPriorAuthenticationInfo()) {
            $array['threeDSRequestorPriorAuthenticationInfo'] = self::threeDSRequestorPriorAuthenticationInfoToArray($threeDSRequestorPriorAuthenticationInfo);
        }

        if ($acctInfo = $strongAuth->getAcctInfo()) {
            $array['acctInfo'] = self::accountInfoToArray($acctInfo);
        }

        if ($merchantRiskIndicator = $strongAuth->getMerchantRiskIndicator()) {
            $array['merchantRiskIndicator'] = self::merchantRiskIndicatorToArray($merchantRiskIndicator);
        }

        if ($threeDSExemptionIndicator = $strongAuth->getThreeDSExemptionIndicator()) {
            $array['threeDSExemptionIndicator'] = $threeDSExemptionIndicator;
        }

        if ($website = $strongAuth->getWebsite()) {
            $array['website'] = $website;
        }

        return $array;
    }

    /**
     * @return array<string,mixed>
     */
    public static function threeDSRequestorAuthenticationInfoToArray(ThreeDSRequestorAuthenticationInfoInterface $authInfo): array
    {
        $array = [];

        if ($threeDSReqAuthData = $authInfo->getThreeDSReqAuthData()) {
            $array['threeDSReqAuthData'] = $threeDSReqAuthData;
        }

        if ($threeDSReqAuthMethod = $authInfo->getThreeDSReqAuthMethod()) {
            $array['threeDSReqAuthMethod'] = $threeDSReqAuthMethod;
        }

        if ($threeDSReqAuthTime = $authInfo->getThreeDSReqAuthTime()) {
            $array['threeDSReqAuthTimestamp'] = $threeDSReqAuthTime->getTimestamp();
        }

        return $array;
    }

    /**
     * @return array<string,string|int>
     */
    public static function threeDSRequestorPriorAuthenticationInfoToArray(ThreeDSRequestorPriorAuthenticationInfoInterface $priorAuthInfo): array
    {
        $array = [];

        if ($threeDSReqPriorAuthData = $priorAuthInfo->getThreeDSReqPriorAuthData()) {
            $array['threeDSReqPriorAuthData'] = $threeDSReqPriorAuthData;
        }

        if ($threeDSReqPriorAuthMethod = $priorAuthInfo->getThreeDSReqPriorAuthMethod()) {
            $array['threeDSReqPriorAuthMethod'] = $threeDSReqPriorAuthMethod;
        }

        if ($threeDSReqPriorAuthTime = $priorAuthInfo->getThreeDSReqPriorAuthTime()) {
            $array['threeDSReqPriorAuthTimestamp'] = $threeDSReqPriorAuthTime->getTimestamp();
        }

        if ($threeDSReqPriorRef = $priorAuthInfo->getThreeDSReqPriorRef()) {
            $array['threeDSReqPriorRef'] = $threeDSReqPriorRef;
        }

        return $array;
    }

    /**
     * @return array<string,string|int|\DateTimeInterface>
     */
    public static function accountInfoToArray(AccountInfoInterface $accountInfo): array
    {
        $array = [];

        if ($chAccAgeInd = $accountInfo->getChAccAgeInd()) {
            $array['chAccAgeInd'] = $chAccAgeInd;
        }

        if ($chAccChange = $accountInfo->getChAccChange()) {
            $array['chAccChange'] = $chAccChange;
        }

        if ($chAccChangeInd = $accountInfo->getChAccChangeInd()) {
            $array['chAccChangeInd'] = $chAccChangeInd;
        }

        if ($chAccDate = $accountInfo->getChAccDate()) {
            $array['chAccDate'] = $chAccDate;
        }

        if ($chAccPwChange = $accountInfo->getChAccPwChange()) {
            $array['chAccPwChange'] = $chAccPwChange;
        }

        if ($chAccPwChangeInd = $accountInfo->getChAccPwChangeInd()) {
            $array['chAccPwChangeInd'] = $chAccPwChangeInd;
        }

        if ($nbPurchaseAccount = $accountInfo->getNbPurchaseAccount()) {
            $array['nbPurchaseAccount'] = $nbPurchaseAccount;
        }

        if ($provisionAttemptsDay = $accountInfo->getProvisionAttemptsDay()) {
            $array['provisionAttemptsDay'] = $provisionAttemptsDay;
        }

        if ($txnActivityDay = $accountInfo->getTxnActivityDay()) {
            $array['txnActivityDay'] = $txnActivityDay;
        }

        if ($txnActivityYear = $accountInfo->getTxnActivityYear()) {
            $array['txnActivityYear'] = $txnActivityYear;
        }

        if ($paymentAccAge = $accountInfo->getPaymentAccAge()) {
            $array['paymentAccAge'] = $paymentAccAge;
        }

        if ($paymentAccInd = $accountInfo->getPaymentAccInd()) {
            $array['paymentAccInd'] = $paymentAccInd;
        }

        if ($shipAddressUsage = $accountInfo->getShipAddressUsage()) {
            $array['shipAddressUsage'] = $shipAddressUsage;
        }

        if ($shipAddressUsageInd = $accountInfo->getShipAddressUsageInd()) {
            $array['shipAddressUsageInd'] = $shipAddressUsageInd;
        }

        if ($shipNameIndicator = $accountInfo->getShipNameIndicator()) {
            $array['shipNameIndicator'] = $shipNameIndicator;
        }

        if ($suspiciousAccActivity = $accountInfo->getSuspiciousAccActivity()) {
            $array['suspiciousAccActivity'] = $suspiciousAccActivity;
        }

        return $array;
    }

    /**
     * @return array<string,string|int>
     */
    public static function merchantRiskIndicatorToArray(MerchantRiskIndicatorInterface $merchantRiskIndicator): array
    {
        $array = [];

        if ($deliveryEmailAddress = $merchantRiskIndicator->getDeliveryEmailAddress()) {
            $array['deliveryEmailAddress'] = $deliveryEmailAddress;
        }

        if ($deliveryTimeframe = $merchantRiskIndicator->getDeliveryTimeframe()) {
            $array['deliveryTimeframe'] = $deliveryTimeframe;
        }

        if ($giftCardAmount = $merchantRiskIndicator->getGiftCardAmount()) {
            $array['giftCardAmount'] = $giftCardAmount;
        }

        if ($giftCardCount = $merchantRiskIndicator->getGiftCardCount()) {
            $array['giftCardCount'] = $giftCardCount;
        }

        if ($preOrderDate = $merchantRiskIndicator->getPreOrderDate()) {
            // this is only assumption that API expects the date in this format.
            // I leave it as it is until I test it or accidentally commit
            $array['preOrderDate'] = $preOrderDate->format('Ymd');
        }

        if ($preOrderPurchaseInd = $merchantRiskIndicator->getPreOrderPurchaseInd()) {
            $array['preOrderPurchaseInd'] = $preOrderPurchaseInd;
        }

        if ($reorderItemsInd = $merchantRiskIndicator->getReorderItemsInd()) {
            $array['reorderItemsInd'] = $reorderItemsInd;
        }

        if ($shipIndicator = $merchantRiskIndicator->getShipIndicator()) {
            $array['shipIndicator'] = $shipIndicator;
        }

        return $array;
    }
}
