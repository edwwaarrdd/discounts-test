<?php

namespace App\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money as MoneyPHP;
use Money\Parser\DecimalMoneyParser;

class Money
{
    public const string EUR = 'EUR';
    private function __construct(private readonly MoneyPHP $internalMoneyObject)
    {
    }

    public static function fromDecimal(string $amount, string $currency): Money
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        $money = $moneyParser->parse($amount, new Currency($currency));

        return new self($money);
    }

    public function equals(self $money): bool
    {
        return $this->internalMoneyObject->equals($money->internalMoneyObject);
    }

    public function toDecimal(): string
    {
        $decimalFormatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $decimalFormatter->format($this->internalMoneyObject);
    }
}
