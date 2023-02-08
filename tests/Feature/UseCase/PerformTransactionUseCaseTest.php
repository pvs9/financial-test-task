<?php

use Finance\Application\Provider\Transaction\TransactionProvider;
use Finance\Application\Provider\Transaction\TransactionProviderFactory;
use Finance\Application\Repository\AccountRepositoryInterface;
use Finance\Application\Repository\TransactionRepositoryInterface;
use Finance\Application\Support\EntitySorter;
use Finance\Application\UseCase\GetAccountTransactionsSorted\GetAccountTransactionsSortedInteractor;
use Finance\Application\UseCase\GetAccountTransactionsSorted\GetAccountTransactionsSortedOutputPort;
use Finance\Application\UseCase\GetAccountTransactionsSorted\GetAccountTransactionsSortedRequestModel;
use Finance\Application\UseCase\PerformTransaction\PerformTransactionInteractor;
use Finance\Application\UseCase\PerformTransaction\PerformTransactionOutputPort;
use Finance\Application\UseCase\PerformTransaction\PerformTransactionRequestModel;
use Finance\Application\UseCase\PerformTransaction\PerformTransactionResponseModel;
use Finance\Domain\Account\AccountEntity;
use Finance\Domain\Account\Transaction\TransactionEntity;
use Finance\Domain\Account\Transaction\TransactionFactory;
use Finance\Domain\Account\Transaction\TransactionListEntity;
use Finance\Domain\Account\Transaction\TransactionType;
use Tests\Feature\UseCase\Support\FakeViewModel;

it('uses accountDoesNotExist when no account is found', function () {
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->expect(
            find: fn ($accountId) => null,
        );
    $transactionRepository = mock(TransactionRepositoryInterface::class)
        ->shouldNotReceive('create')
        ->getMock();
    $transactionFactory = mock(TransactionFactory::class)
        ->shouldNotReceive('make')
        ->getMock();
    $transactionProviderFactory = mock(TransactionProviderFactory::class)
        ->shouldNotReceive('createFromTransaction')
        ->getMock();
    $outputPort = mock(PerformTransactionOutputPort::class)
        ->shouldReceive('accountDoesNotExist')
        ->atLeast()
        ->once()
        ->getMock();
    $useCase = new PerformTransactionInteractor(
        $outputPort,
        $accountRepository,
        $transactionFactory,
        $transactionProviderFactory,
        $transactionRepository
    );
    $useCase->performTransaction(
        new PerformTransactionRequestModel(
            1,
            TransactionType::DEPOSIT->value,
            'Test comment',
            1.05
        )
    );
});

it('uses recipientDoesNotExist when no recipient account is found', function () {
    $accountEntity = mock(AccountEntity::class)
        ->shouldNotReceive('getId')
        ->getMock();
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->shouldReceive('find')
        ->twice()
        ->andReturnValues([
            $accountEntity, null
        ])
        ->getMock();
    $transactionRepository = mock(TransactionRepositoryInterface::class)
        ->shouldNotReceive('create')
        ->getMock();
    $transactionFactory = mock(TransactionFactory::class)
        ->shouldNotReceive('make')
        ->getMock();
    $transactionProviderFactory = mock(TransactionProviderFactory::class)
        ->shouldNotReceive('createFromTransaction')
        ->getMock();
    $outputPort = mock(PerformTransactionOutputPort::class)
        ->shouldReceive('recipientDoesNotExist')
        ->once()
        ->getMock();
    $useCase = new PerformTransactionInteractor(
        $outputPort,
        $accountRepository,
        $transactionFactory,
        $transactionProviderFactory,
        $transactionRepository
    );
    $useCase->performTransaction(
        new PerformTransactionRequestModel(
            1,
            TransactionType::DEPOSIT->value,
            'Test comment',
            1.05,
            1
        )
    );
});

it('uses unableToPerformTransaction on repository exception', function () {
    $accountEntity = mock(AccountEntity::class)
        ->shouldNotReceive('getId')
        ->getMock();
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->shouldReceive('find')
        ->twice()
        ->andReturnValues([
            $accountEntity, $accountEntity
        ])
        ->getMock();
    $transactionEntity = mock(TransactionEntity::class)
        ->shouldNotReceive('getId')
        ->getMock();
    $transactionRepository = mock(TransactionRepositoryInterface::class)
        ->shouldReceive('create')
        ->andThrow(new Exception())
        ->getMock();
    $transactionFactory = mock(TransactionFactory::class)
        ->expect(
            make: fn ($transaction) => $transactionEntity,
        );
    $transactionProviderFactory = mock(TransactionProviderFactory::class)
        ->shouldNotReceive('createFromTransaction')
        ->getMock();
    $outputPort = mock(PerformTransactionOutputPort::class)
        ->shouldReceive('unableToPerformTransaction')
        ->once()
        ->getMock();
    $useCase = new PerformTransactionInteractor(
        $outputPort,
        $accountRepository,
        $transactionFactory,
        $transactionProviderFactory,
        $transactionRepository
    );
    $useCase->performTransaction(
        new PerformTransactionRequestModel(
            1,
            TransactionType::DEPOSIT->value,
            'Test comment',
            1.05,
            1
        )
    );
});

it('compensates saga on provider failure', function () {
    $accountEntity = mock(AccountEntity::class)
        ->shouldNotReceive('getId')
        ->getMock();
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->shouldReceive('find')
        ->twice()
        ->andReturnValues([
            $accountEntity, $accountEntity
        ])
        ->getMock();
    $transactionEntity = mock(TransactionEntity::class)
        ->shouldNotReceive('getId')
        ->getMock();
    $transactionRepository = mock(TransactionRepositoryInterface::class)
        ->expect(
            create: fn ($transaction) => $transactionEntity,
            delete: fn ($transaction) => $transactionEntity,
        );
    $transactionFactory = mock(TransactionFactory::class)
        ->expect(
            make: fn ($transaction) => $transactionEntity,
        );
    $transactionProvider = mock(TransactionProvider::class)
        ->shouldReceive('handle')
        ->andThrow(new Exception())
        ->getMock()
        ->shouldReceive('compensate')
        ->getMock();
    $transactionProviderFactory = mock(TransactionProviderFactory::class)
        ->expect(
            createFromTransaction: fn ($transactionEntity) => $transactionProvider,
        );
    $outputPort = mock(PerformTransactionOutputPort::class)
        ->shouldReceive('unableToPerformTransaction')
        ->once()
        ->getMock();
    $useCase = new PerformTransactionInteractor(
        $outputPort,
        $accountRepository,
        $transactionFactory,
        $transactionProviderFactory,
        $transactionRepository
    );
    $useCase->performTransaction(
        new PerformTransactionRequestModel(
            1,
            TransactionType::DEPOSIT->value,
            'Test comment',
            1.05,
            1
        )
    );
});

it('returns transaction id on success', function () {
    $accountEntity = mock(AccountEntity::class)
        ->shouldNotReceive('getId')
        ->getMock();
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->shouldReceive('find')
        ->twice()
        ->andReturnValues([
            $accountEntity, $accountEntity
        ])
        ->getMock();
    $transactionEntity = mock(TransactionEntity::class)
        ->expect(
            getId: fn () => 123,
        );
    $transactionRepository = mock(TransactionRepositoryInterface::class)
        ->expect(
            create: fn ($transaction) => $transactionEntity,
        );
    $transactionFactory = mock(TransactionFactory::class)
        ->expect(
            make: fn ($transaction) => $transactionEntity,
        );
    $transactionProvider = mock(TransactionProvider::class)
        ->shouldReceive('handle')
        ->getMock()
        ->shouldNotReceive('compensate')
        ->getMock();
    $transactionProviderFactory = mock(TransactionProviderFactory::class)
        ->expect(
            createFromTransaction: fn ($transactionEntity) => $transactionProvider,
        );
    $outputPort = mock(PerformTransactionOutputPort::class)
        ->expect(
            performedTransaction: fn ($responseModel) => new FakeViewModel($responseModel),
        );
    $useCase = new PerformTransactionInteractor(
        $outputPort,
        $accountRepository,
        $transactionFactory,
        $transactionProviderFactory,
        $transactionRepository
    );
    $result = $useCase->performTransaction(
        new PerformTransactionRequestModel(
            1,
            TransactionType::DEPOSIT->value,
            'Test comment',
            1.05,
            1
        )
    )->getValue();

    expect($result)->toBeInstanceOf(PerformTransactionResponseModel::class)
        ->and($result->getTransactionId())
        ->toEqual(123);
});