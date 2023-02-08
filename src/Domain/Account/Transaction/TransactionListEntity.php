<?php

declare(strict_types=1);

namespace Finance\Domain\Account\Transaction;

interface TransactionListEntity
{
    /**
     * @return TransactionEntity[]
     */
    public function getTransactions(): array;
}
