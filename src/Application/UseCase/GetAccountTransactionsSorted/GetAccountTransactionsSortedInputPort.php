<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountTransactionsSorted;

use Finance\Domain\ViewModel;

interface GetAccountTransactionsSortedInputPort
{
    public function getSortedTransactions(GetAccountTransactionsSortedRequestModel $model): ViewModel;
}
