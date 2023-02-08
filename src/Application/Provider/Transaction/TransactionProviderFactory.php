<?php

declare(strict_types=1);

namespace Finance\Application\Provider\Transaction;

use Finance\Domain\Account\Transaction\TransactionEntity;

interface TransactionProviderFactory
{
    public function createFromTransaction(TransactionEntity $transactionEntity): TransactionProvider;
}
