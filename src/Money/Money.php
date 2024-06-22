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

    /**
     * @param  non-empty-string  $amount
     * @param  non-empty-string  $currency
     */
    public static function fromDecimal(string $amount, string $currency): Money
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        $money = $moneyParser->parse($amount, new Currency($currency));

        return new self($money);
    }

    /**
     * @param  non-empty-string  $currency
     */
    public static function zero(string $currency): Money
    {
        return new self(new MoneyPHP('0', new Currency($currency)));
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

    public function isMoreThan(self $money): bool
    {
        return $this->internalMoneyObject->greaterThan($money->internalMoneyObject);
    }

    /**
     * @param  numeric-string|int  $param
     *
     * @return Money
     */
    public function multiply(string|int $param): Money
    {
        return new self($this->internalMoneyObject->multiply($param));
    }

    public function add(self $money): Money
    {
        return new self($this->internalMoneyObject->add($money->internalMoneyObject));
    }

    public function isZero(): bool
    {
        return $this->internalMoneyObject->isZero();
    }
}
