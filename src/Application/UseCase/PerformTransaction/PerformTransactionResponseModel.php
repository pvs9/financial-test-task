<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\PerformTransaction;

class PerformTransactionResponseModel
{
    public function __construct(
        private int $transactionId
    ) {
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }
}
