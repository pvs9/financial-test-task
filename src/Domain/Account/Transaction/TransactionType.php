<?php

declare(strict_types=1);

namespace Finance\Domain\Account\Transaction;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case TRANSFER = 'transfer';
    case WITHDRAWAL = 'withdrawal';
}
