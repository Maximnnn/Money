<?php
namespace Money\Interfaces;

interface MoneyInterface
{
    public function getValue(): string;

    public function getCurrency(): CurrencyInterface;
}