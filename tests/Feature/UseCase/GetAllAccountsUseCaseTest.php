<?php

use Finance\Application\Repository\AccountRepositoryInterface;
use Finance\Application\UseCase\GetAllAccounts\GetAllAccountsInteractor;
use Finance\Application\UseCase\GetAllAccounts\GetAllAccountsOutputPort;
use Finance\Application\UseCase\GetAllAccounts\GetAllAccountsResponseModel;
use Finance\Domain\Account\AccountEntity;
use Tests\Feature\UseCase\Support\FakeViewModel;

it('uses noAccounts when no accounts are found', function () {
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->expect(
            findAll: fn () => [],
        );
    $outputPort = mock(GetAllAccountsOutputPort::class)
        ->shouldReceive('noAccounts')
        ->atLeast()
        ->once()
        ->getMock();

    $useCase = new GetAllAccountsInteractor($outputPort, $accountRepository);
    $useCase->getAccounts();
});

it('uses unableToGetAccounts when exception is thrown', function () {
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->shouldReceive('findAll')
        ->atLeast()
        ->once()
        ->andThrow(new Exception())
        ->getMock();
    $outputPort = mock(GetAllAccountsOutputPort::class)
        ->shouldReceive('unableToGetAccounts')
        ->atLeast()
        ->once()
        ->getMock();

    $useCase = new GetAllAccountsInteractor($outputPort, $accountRepository);
    $useCase->getAccounts();
});

it('returns array of accounts on success', function () {
    $accountEntity = mock(AccountEntity::class)
        ->expect(
            getId: fn () => 123,
        );
    $accountRepository = mock(AccountRepositoryInterface::class)
        ->expect(
            findAll: fn () => [$accountEntity],
        );
    $outputPort = mock(GetAllAccountsOutputPort::class)
        ->expect(
            allAccounts: fn ($responseModel) => new FakeViewModel($responseModel),
        );

    $useCase = new GetAllAccountsInteractor($outputPort, $accountRepository);
    $result = $useCase->getAccounts()
        ->getValue();
    expect($result)
        ->toBeInstanceOf(GetAllAccountsResponseModel::class);

    $accounts = $result->getAccounts();
    expect($accounts)->toHaveCount(1)
        ->and($accounts[0]->getId())
        ->toEqual(123);
});