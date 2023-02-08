<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountBalance;

class GetAccountBalanceRequestModel
{
    public function __construct(
        private int $accountId
    ) {
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }
}
