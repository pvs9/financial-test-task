<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAllAccounts;

use Finance\Domain\ViewModel;

interface GetAllAccountsInputPort
{
    public function getAccounts(): ViewModel;
}
