<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountTransactionsSorted;

use Assert\Assertion;

class GetAccountTransactionsSortedRequestModel
{
    public function __construct(
        private int $accountId,
        private string $sortBy = 'comment',
        private string $sortDirection = 'asc',
        private ?int $sortMode = SORT_REGULAR
    ) {
        Assertion::inArray($sortBy, ['comment', 'dueDate']);
        Assertion::inArray($sortDirection, ['asc', 'desc']);
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    public function getSortMode(): ?int
    {
        return $this->sortMode;
    }
}
