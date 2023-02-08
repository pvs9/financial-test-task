<?php

declare(strict_types=1);

namespace Finance\Domain\Account;

use Finance\Domain\Account\Transaction\TransactionListEntity;

interface AccountEntity
{
    public function getId(): ?int;

    public function getBalance(): float;

    public function getTransactions(): TransactionListEntity;
}
