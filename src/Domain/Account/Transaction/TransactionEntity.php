<?php

declare(strict_types=1);

namespace Finance\Domain\Account\Transaction;

use DateTime;
use Finance\Domain\Account\AccountEntity;

interface TransactionEntity
{
    public function getId(): ?int;

    public function getType(): TransactionType;

    public function getComment(): string;

    public function getAmount(): float;

    public function getDueDate(): DateTime;

    public function getOwner(): AccountEntity;

    public function getRecipient(): ?AccountEntity;
}
