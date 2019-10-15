<?php
namespace Money;

use Money\Interfaces\CurrencyInterface;
use Money\Interfaces\ExchangeRateProviderInterface;
use Money\Interfaces\MoneyInterface;

class MoneyService
{
    /**
     * @var ExchangeRateProviderInterface
     */
    private $exchangeRateProvider;

    public function __construct(ExchangeRateProviderInterface $exchangeRateProvider)
    {
        $this->exchangeRateProvider = $exchangeRateProvider;
    }

    public function canExchange(MoneyInterface $money, CurrencyInterface $toCurrency): bool
    {
        return !is_null($this->exchangeRateProvider->getRate($money->getCurrency(), $toCurrency));
    }

    public function exchange(MoneyInterface $money, CurrencyInterface $toCurrency): MoneyInterface
    {
        $rate = $this->exchangeRateProvider->getRate($money->getCurrency(), $toCurrency);

        $this->validateNumeric($rate);

        $class = get_class($money);
        return new $class(bcmul($money->getValue(), $rate, 2), $toCurrency);
    }

    public function sum(MoneyInterface $money, MoneyInterface $operand): MoneyInterface
    {
        $operand = $this->exchange($operand, $money->getCurrency());

        return $this->clone($money, bcadd($money->getValue(), $operand->getValue(), 2));
    }

    public function subtract(MoneyInterface $money, MoneyInterface $subtrahend): MoneyInterface
    {
        $subtrahend = $this->exchange($subtrahend, $money->getCurrency());

        return $this->clone($money, bcsub($money->getValue(), $subtrahend->getValue(), 2));
    }

    public function divide(MoneyInterface $money, string $divider): MoneyInterface
    {
        $this->validateNumeric($divider);

        return $this->clone($money, bcdiv($money->getValue(), $divider, 2));
    }

    public function multiple(MoneyInterface $money, string $factor): MoneyInterface
    {
        $this->validateNumeric($factor);

        return $this->clone($money, bcmul($money->getValue(), $factor, 2));
    }

    private function clone(MoneyInterface $money, string $newValue): MoneyInterface
    {
        $class = get_class($money);
        return new $class($newValue, $money->getCurrency());
    }

    private function validateNumeric(string $value)
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('value is not accepted');
        }
    }

}