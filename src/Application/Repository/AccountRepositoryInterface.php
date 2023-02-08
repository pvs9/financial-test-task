<?php

declare(strict_types=1);

namespace Finance\Application\Repository;

use Finance\Domain\Account\AccountEntity;

interface AccountRepositoryInterface
{
    public function find(int $accountId): ?AccountEntity;

    public function findWithTransactions(int $accountId): ?AccountEntity;

    /**
     * @return AccountEntity[]
     */
    public function findAll(): array;
}
