<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAllAccounts;

use Assert\Assertion;
use Finance\Domain\Account\AccountEntity;

class GetAllAccountsResponseModel
{
    /**
     * @param AccountEntity[] $accounts
     */
    public function __construct(
        private array $accounts
    ) {
        Assertion::allIsInstanceOf($accounts, AccountEntity::class);
    }

    /**
     * @return AccountEntity[]
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }
}
