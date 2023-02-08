<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAllAccounts;

use Finance\Domain\ViewModel;

interface GetAllAccountsOutputPort
{
    public function allAccounts(GetAllAccountsResponseModel $model): ViewModel;

    public function noAccounts(GetAllAccountsResponseModel $model): ViewModel;

    public function unableToGetAccounts(\Throwable $e): ViewModel;
}
