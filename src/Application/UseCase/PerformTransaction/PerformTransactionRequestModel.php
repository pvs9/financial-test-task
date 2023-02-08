<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\PerformTransaction;

use Assert\Assertion;
use Finance\Domain\Account\Transaction\TransactionType;

class PerformTransactionRequestModel
{
    public function __construct(
        private int $accountId,
        private string $type,
        private string $comment,
        private float $amount,
        private ?int $recipientId = null
    ) {
        Assertion::inArray($type, TransactionType::cases());
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getRecipientId(): ?int
    {
        return $this->recipientId;
    }
}
