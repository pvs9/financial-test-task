<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountBalance;

class GetAccountBalanceResponseModel
{
    public function __construct(
        private float $balance
    ) {
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}
