<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountTransactionsSorted;

use Assert\Assertion;
use Finance\Domain\Account\Transaction\TransactionEntity;

class GetAccountTransactionsSortedResponseModel
{
    /**
     * @param TransactionEntity[] $transactions
     */
    public function __construct(
        private array $transactions
    ) {
        Assertion::allIsInstanceOf($transactions, TransactionEntity::class);
    }

    /**
     * @return TransactionEntity[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }
}
