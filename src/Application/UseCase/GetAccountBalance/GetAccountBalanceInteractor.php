<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountBalance;

use Finance\Application\Repository\AccountRepositoryInterface;
use Finance\Domain\ViewModel;

class GetAccountBalanceInteractor implements GetAccountBalanceInputPort
{
    public function __construct(
        private GetAccountBalanceOutputPort $outputPort,
        private AccountRepositoryInterface $accountRepository
    ) {
    }

    public function getAccountBalance(GetAccountBalanceRequestModel $model): ViewModel
    {
        try {
            $account = $this->accountRepository->find($model->getAccountId());

            if (is_null($account)) {
                return $this->outputPort->accountDoesNotExist();
            }

            return $this->outputPort->accountBalance(
                new GetAccountBalanceResponseModel($account->getBalance())
            );
        } catch (\Exception $e) {
            return $this->outputPort->unableToGetAccountBalance($e);
        }
    }
}
