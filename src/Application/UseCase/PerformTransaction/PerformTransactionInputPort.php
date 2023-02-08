<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\PerformTransaction;

use Finance\Domain\ViewModel;

interface PerformTransactionInputPort
{
    public function performTransaction(PerformTransactionRequestModel $model): ViewModel;
}
