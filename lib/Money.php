<?php
namespace Money;

use Money\Interfaces\CurrencyInterface;
use Money\Interfaces\MoneyInterface;

class Money implements MoneyInterface
{

    /**
     * @var string
     */
    private $value;
    /**
     * @var CurrencyInterface
     */
    private $currency;

    public function __construct(string $value, CurrencyInterface $currency)
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('value is not accepted');
        }

        $this->value    = $value;
        $this->currency = $currency;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCurrency(): CurrencyInterface
    {
        return $this->currency;
    }
}