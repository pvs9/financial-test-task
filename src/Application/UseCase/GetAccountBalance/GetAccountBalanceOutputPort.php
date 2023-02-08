<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountBalance;

use Finance\Domain\ViewModel;

interface GetAccountBalanceOutputPort
{
    public function accountBalance(GetAccountBalanceResponseModel $model): ViewModel;

    public function accountDoesNotExist(): ViewModel;

    public function unableToGetAccountBalance(\Throwable $e): ViewModel;
}
