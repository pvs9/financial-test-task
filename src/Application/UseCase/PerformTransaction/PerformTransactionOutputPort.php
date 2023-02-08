<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\PerformTransaction;

use Finance\Domain\ViewModel;

interface PerformTransactionOutputPort
{
    public function performedTransaction(PerformTransactionResponseModel $model): ViewModel;

    public function accountDoesNotExist(): ViewModel;

    public function recipientDoesNotExist(): ViewModel;

    public function unableToPerformTransaction(\Throwable $e): ViewModel;
}
