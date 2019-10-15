<?php


namespace Money\Interfaces;


interface ExchangeRateProviderInterface
{
    public function getRate(CurrencyInterface $from, CurrencyInterface $to): ?string;
}