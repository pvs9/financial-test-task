<?php

use Assert\InvalidArgumentException;
use Finance\Application\UseCase\GetAccountTransactionsSorted\GetAccountTransactionsSortedRequestModel;

it('fails on wrong sortBy column', function () {
    new GetAccountTransactionsSortedRequestModel(1, 'test_column');
})->throws(InvalidArgumentException::class);

it('fails on wrong sortDirection', function () {
    new GetAccountTransactionsSortedRequestModel(1, 'comment', 'testDirection');
})->throws(InvalidArgumentException::class);