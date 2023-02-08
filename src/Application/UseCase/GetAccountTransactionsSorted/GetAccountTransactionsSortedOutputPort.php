<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountTransactionsSorted;

use Finance\Application\UseCase\GetAllAccounts\GetAllAccountsResponseModel;
use Finance\Domain\ViewModel;

interface GetAccountTransactionsSortedOutputPort
{
    public function sortedTransactions(GetAccountTransactionsSortedResponseModel $model): ViewModel;

    public function accountDoesNotExist(): ViewModel;

    public function noTransactions(GetAccountTransactionsSortedResponseModel $model): ViewModel;

    public function unableToGetTransactions(\Throwable $e): ViewModel;
}
