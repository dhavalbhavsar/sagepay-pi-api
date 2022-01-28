<?php

namespace Lumnn\SagePayPi\Tests\Cards;

use Lumnn\SagePayPi\Card\CardDetails;
use Lumnn\SagePayPi\Card\CardIdentifierInterface;
use Lumnn\SagePayPi\Card\Cards;
use Lumnn\SagePayPi\Exceptions\ValidationException;
use Lumnn\SagePayPi\SagePayPi;
use Lumnn\SagePayPi\Tests\SagePayPiTest;
use PHPUnit\Framework\TestCase;

class CardsTest extends TestCase
{
    // test card number obtained from Api Reference
    public const TEST_CARD_NUMBER = '4917300000000008';

    protected SagePayPi $sagepayPi;

    protected Cards $cards;

    protected function setUp(): void
    {
        $this->sagepayPi = SagePayPiTest::createLiveTestInstance();
        $this->cards = new Cards($this->sagepayPi);
    }

    public function testCardCreate(): void
    {
        $card = new CardDetails();
        $card
            ->setCardNumber(self::TEST_CARD_NUMBER)
            ->setCardholderName('Test Name')
            ->setExpiryDate('12'.(date('y') + 1))
            ->setSecurityCode('123');

        $cardIdentifier = $this->cards->createIdentifier($card);

        $this->assertInstanceOf(CardIdentifierInterface::class, $cardIdentifier);
    }

    public function testMissingDetails(): void
    {
        $card = new CardDetails();
        $card->setCardNumber(self::TEST_CARD_NUMBER);

        $this->expectException(ValidationException::class);
        $this->cards->createIdentifier($card);
    }
}
