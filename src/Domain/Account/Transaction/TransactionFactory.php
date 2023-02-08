<?php

namespace Finance\Domain\Account\Transaction;

interface TransactionFactory
{
    /**
     * @param array<string, mixed> $attributes
     * @return TransactionEntity
     */
    public function make(array $attributes = []): TransactionEntity;
}
