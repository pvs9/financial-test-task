<?php

use Finance\Application\Support\NativeEntitySorter;
use Finance\Domain\Account\Transaction\TransactionEntity;

it('sorts by string property correctly', function () {
    $entityLast = mock(TransactionEntity::class)
        ->expect(
            getComment: fn () => 'last_entity_comment'
        );
    $entityFirst = mock(TransactionEntity::class)
        ->expect(
            getComment: fn () => 'first_entity_comment'
        );

    $sorter = new NativeEntitySorter();
    $result = $sorter->sort(
        [
            $entityLast,
            $entityFirst,
        ],
        'comment',
        'asc',
        SORT_STRING
    );

    expect($result)
        ->toBeArray()
        ->toHaveCount(2)
        ->and($result[0]->getComment())
        ->toEqual('first_entity_comment')
        ->and($result[1]->getComment())
        ->toEqual('last_entity_comment');
});

it('sorts desc by string property correctly', function () {
    $entityLast = mock(TransactionEntity::class)
        ->expect(
            getComment: fn () => 'last_entity_comment'
        );
    $entityFirst = mock(TransactionEntity::class)
        ->expect(
            getComment: fn () => 'first_entity_comment'
        );

    $sorter = new NativeEntitySorter();
    $result = $sorter->sort(
        [
            $entityLast,
            $entityFirst,
        ],
        'comment',
        'desc',
        SORT_STRING
    );

    expect($result)
        ->toBeArray()
        ->toHaveCount(2)
        ->and($result[0]->getComment())
        ->toEqual('last_entity_comment')
        ->and($result[1]->getComment())
        ->toEqual('first_entity_comment');
});

it('sorts desc by DateTime property correctly', function () {
    $entityLast = mock(TransactionEntity::class)
        ->expect(
            getId: fn () => 2,
            getDueDate: fn () => new DateTime('now')
        );
    $entityFirst = mock(TransactionEntity::class)
        ->expect(
            getId: fn () => 1,
            getDueDate: fn () => new DateTime('yesterday')
        );

    $sorter = new NativeEntitySorter();
    $result = $sorter->sort(
        [
            $entityLast,
            $entityFirst,
        ],
        'dueDate',
        'desc'
    );

    expect($result)
        ->toBeArray()
        ->toHaveCount(2)
        ->and($result[0]->getId())
        ->toEqual(2)
        ->and($result[1]->getId())
        ->toEqual(1);
});

it('sorts by DateTime property correctly', function () {
    $entityLast = mock(TransactionEntity::class)
        ->expect(
            getId: fn () => 2,
            getDueDate: fn () => new DateTime('now')
        );
    $entityFirst = mock(TransactionEntity::class)
        ->expect(
            getId: fn () => 1,
            getDueDate: fn () => new DateTime('yesterday')
        );

    $sorter = new NativeEntitySorter();
    $result = $sorter->sort(
        [
            $entityLast,
            $entityFirst,
        ],
        'dueDate',
        'asc'
    );

    expect($result)
        ->toBeArray()
        ->toHaveCount(2)
        ->and($result[0]->getId())
        ->toEqual(1)
        ->and($result[1]->getId())
        ->toEqual(2);
});