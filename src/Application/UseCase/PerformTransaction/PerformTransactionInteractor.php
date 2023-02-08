<?php

declare(strict_types=1);

namespace Finance\Application\UseCase\PerformTransaction;

use Finance\Application\Provider\Transaction\TransactionProviderFactory;
use Finance\Application\Repository\AccountRepositoryInterface;
use Finance\Application\Repository\TransactionRepositoryInterface;
use Finance\Application\Support\Saga;
use Finance\Domain\Account\Transaction\TransactionFactory;
use Finance\Domain\Account\Transaction\TransactionType;
use Finance\Domain\ViewModel;

class PerformTransactionInteractor implements PerformTransactionInputPort
{
    public function __construct(
        private PerformTransactionOutputPort $outputPort,
        private AccountRepositoryInterface $accountRepository,
        private TransactionFactory $transactionFactory,
        private TransactionProviderFactory $transactionProviderFactory,
        private TransactionRepositoryInterface $transactionRepository
    ) {
    }

    public function performTransaction(PerformTransactionRequestModel $model): ViewModel
    {
        $account = $this->accountRepository->find($model->getAccountId());

        if (is_null($account)) {
            return $this->outputPort->accountDoesNotExist();
        }

        $recipient = $model->getRecipientId();

        if (!is_null($recipient)) {
            $recipient = $this->accountRepository->find($recipient);

            if (is_null($recipient)) {
                return $this->outputPort->recipientDoesNotExist();
            }
        }

        $transaction = $this->transactionFactory->make([
            'owner' => $account,
            'type' => TransactionType::from($model->getType()),
            'comment' => $model->getComment(),
            'amount' => $model->getAmount(),
            'recipient' => $recipient,
        ]);

        $saga = new Saga();

        try {
            $transaction = $this->transactionRepository->create($transaction);
            $saga->addCompensation(fn() => $this->transactionRepository->delete($transaction));

            $transactionProvider = $this->transactionProviderFactory->createFromTransaction($transaction);
            $saga->addCompensation(fn() => $transactionProvider->compensate($transaction));
            $transactionProvider->handle($transaction);
        } catch (\Exception $e) {
            $saga->compensate();

            return $this->outputPort->unableToPerformTransaction($e);
        }

        return $this->outputPort->performedTransaction(
            new PerformTransactionResponseModel($transaction->getId())
        );
    }
}
