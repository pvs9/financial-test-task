<?php

use Finance\Application\Repository\AccountRepositoryInterface;
use Finance\Application\UseCase\GetAccountBalance\GetAccountBalanceInteractor;
use Finance\Application\UseCase\GetAccountBalance\GetAccountBalanceOutputPort;
use Finance\Application\UseCase\GetAccountBalance\GetAccountBalanceRequestModel;
use Finance\Application\UseCase\GetAccountBalance\GetAccountBalanceResponseModel;
use Finance\Domain\Account\AccountEntity;
use Tests\Feature\UseCase\Support\FakeViewModel;

it('uses accountDoesNotExist when no account is found', function () {
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->expect(
            find: fn ($accountId) => null,
        );
    $outputPort = mock(GetAccountBalanceOutputPort::class)
        ->shouldReceive('accountDoesNotExist')
        ->atLeast()
        ->once()
        ->getMock();
    $useCase = new GetAccountBalanceInteractor($outputPort, $accountRepository);
    $useCase->getAccountBalance(new GetAccountBalanceRequestModel(1));
});

it('uses unableToGetAccountBalance when exception is thrown', function () {
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->shouldReceive('find')
        ->andThrow(new Exception())
        ->getMock();
    $outputPort = mock(GetAccountBalanceOutputPort::class)
        ->shouldReceive('unableToGetAccountBalance')
        ->atLeast()
        ->once()
        ->getMock();
    $useCase = new GetAccountBalanceInteractor($outputPort, $accountRepository);
    $useCase->getAccountBalance(new GetAccountBalanceRequestModel(1));
});

it('returns account balance on success', function () {
    $accountEntity = mock(AccountEntity::class)
        ->expect(
            getBalance: fn () => 123,
        );
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->expect(
            find: fn ($accountId) => $accountEntity,
        );
    $outputPort = mock(GetAccountBalanceOutputPort::class)
        ->expect(
            accountBalance: fn ($responseModel) => new FakeViewModel($responseModel),
        );
    $useCase = new GetAccountBalanceInteractor($outputPort, $accountRepository);
    $result = $useCase->getAccountBalance(new GetAccountBalanceRequestModel(1))
        ->getValue();

    expect($result)
        ->toBeInstanceOf(GetAccountBalanceResponseModel::class)
        ->and($result->getBalance())
        ->toEqual(123);
});