<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountBalance;

use Finance\Domain\ViewModel;

interface GetAccountBalanceInputPort
{
    public function getAccountBalance(GetAccountBalanceRequestModel $model): ViewModel;
}
