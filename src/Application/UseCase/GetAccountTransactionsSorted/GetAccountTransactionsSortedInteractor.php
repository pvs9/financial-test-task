<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\GetAccountTransactionsSorted;

use Finance\Application\Repository\AccountRepositoryInterface;
use Finance\Application\Support\EntitySorter;
use Finance\Domain\Account\Transaction\TransactionEntity;
use Finance\Domain\ViewModel;

class GetAccountTransactionsSortedInteractor implements GetAccountTransactionsSortedInputPort
{
    public function __construct(
        private GetAccountTransactionsSortedOutputPort $outputPort,
        private AccountRepositoryInterface $accountRepository,
        private EntitySorter $entitySorter
    ) {
    }

    public function getSortedTransactions(GetAccountTransactionsSortedRequestModel $model): ViewModel
    {
        $account = $this->accountRepository->findWithTransactions($model->getAccountId());

        if (is_null($account)) {
            return $this->outputPort->accountDoesNotExist();
        }

        try {
            $transactions = $account->getTransactions()
                ->getTransactions();

            if (empty($transactions)) {
                return $this->outputPort->noTransactions(
                    new GetAccountTransactionsSortedResponseModel($transactions)
                );
            }

            /** @var TransactionEntity[] $sortedTransactions */
            $sortedTransactions = $this->entitySorter->sort(
                $transactions,
                $model->getSortBy(),
                $model->getSortDirection(),
                $model->getSortMode()
            );

            return $this->outputPort->sortedTransactions(
                new GetAccountTransactionsSortedResponseModel($sortedTransactions)
            );
        } catch (\Exception $e) {
            return $this->outputPort->unableToGetTransactions($e);
        }
    }
}
