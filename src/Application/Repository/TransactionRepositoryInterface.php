<?php

declare(strict_types=1);

namespace Finance\Application\Repository;

use Finance\Domain\Account\Transaction\TransactionEntity;

interface TransactionRepositoryInterface
{
    public function create(TransactionEntity $transaction): TransactionEntity;

    public function delete(TransactionEntity $transaction): void;
}
