<?php

use Finance\Application\Repository\AccountRepositoryInterface;
use Finance\Application\Support\EntitySorter;
use Finance\Application\UseCase\GetAccountTransactionsSorted\GetAccountTransactionsSortedInteractor;
use Finance\Application\UseCase\GetAccountTransactionsSorted\GetAccountTransactionsSortedOutputPort;
use Finance\Application\UseCase\GetAccountTransactionsSorted\GetAccountTransactionsSortedRequestModel;
use Finance\Application\UseCase\GetAccountTransactionsSorted\GetAccountTransactionsSortedResponseModel;
use Finance\Domain\Account\AccountEntity;
use Finance\Domain\Account\Transaction\TransactionEntity;
use Finance\Domain\Account\Transaction\TransactionListEntity;
use Tests\Feature\UseCase\Support\FakeViewModel;

it('uses accountDoesNotExist when no account is found', function () {
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->expect(
            findWithTransactions: fn ($accountId) => null,
        );
    $entitySorter = mock(EntitySorter::class)
        ->shouldNotReceive('sort')
        ->getMock();
    $outputPort = mock(GetAccountTransactionsSortedOutputPort::class)
        ->shouldReceive('accountDoesNotExist')
        ->atLeast()
        ->once()
        ->getMock();
    $useCase = new GetAccountTransactionsSortedInteractor($outputPort, $accountRepository, $entitySorter);
    $useCase->getSortedTransactions(new GetAccountTransactionsSortedRequestModel(1));
});

it('uses unableToGetTransactions when exception is thrown', function () {
    $transactionListEntity = mock(TransactionListEntity::class)
        ->expect(
            getTransactions: fn () => [],
        );
    $accountEntity = mock(AccountEntity::class)
        ->expect(
            getTransactions: fn () => $transactionListEntity,
        );
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->expect(
            findWithTransactions: fn ($accountId) => $accountEntity,
        );
    $entitySorter = mock(EntitySorter::class)
        ->shouldReceive('sort')
        ->andThrow(new Exception())
        ->getMock();
    $outputPort = mock(GetAccountTransactionsSortedOutputPort::class)
        ->shouldReceive('unableToGetTransactions')
        ->atLeast()
        ->once()
        ->getMock();
    $useCase = new GetAccountTransactionsSortedInteractor($outputPort, $accountRepository, $entitySorter);
    $useCase->getSortedTransactions(new GetAccountTransactionsSortedRequestModel(1));
});

it('returns account transactions on success', function () {
    $transactionEntity = mock(TransactionEntity::class)
        ->expect(
            getComment: fn () => 'Test comment',
        );
    $transactionListEntity = mock(TransactionListEntity::class)
        ->expect(
            getTransactions: fn () => [$transactionEntity],
        );
    $accountEntity = mock(AccountEntity::class)
        ->expect(
            getTransactions: fn () => $transactionListEntity,
        );
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->expect(
            findWithTransactions: fn ($accountId) => $accountEntity,
        );
    $entitySorter = mock(EntitySorter::class)
        ->expect(
            sort: fn ($transactions) => $transactions,
        );
    $outputPort = mock(GetAccountTransactionsSortedOutputPort::class)
        ->expect(
            sortedTransactions: fn ($responseModel) => new FakeViewModel($responseModel),
        );
    $useCase = new GetAccountTransactionsSortedInteractor($outputPort, $accountRepository, $entitySorter);
    $result = $useCase->getSortedTransactions(new GetAccountTransactionsSortedRequestModel(1))
        ->getValue();
    expect($result)
        ->toBeInstanceOf(GetAccountTransactionsSortedResponseModel::class);

    $transactions = $result->getTransactions();
    expect($transactions)->toHaveCount(1)
        ->and($transactions[0]->getComment())
        ->toEqual('Test comment');
});