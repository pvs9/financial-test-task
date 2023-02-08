<?php

use Assert\InvalidArgumentException;
use Finance\Application\UseCase\PerformTransaction\PerformTransactionRequestModel;

it('fails on wrong transaction type', function () {
    new PerformTransactionRequestModel(1, 'testType', 'Test comment', 1.05);
})->throws(InvalidArgumentException::class);