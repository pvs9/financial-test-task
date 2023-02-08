<?php

declare(strict_types=1);

namespace Finance\Application\Provider\Transaction;

use Finance\Domain\Account\Transaction\TransactionEntity;

interface TransactionProvider
{
    public function handle(TransactionEntity $transactionEntity): void;

    public function compensate(TransactionEntity $transactionEntity): void;
}
