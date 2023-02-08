<?php

namespace Tests\Feature\UseCase\Support;

use Finance\Domain\ViewModel;

class FakeViewModel implements ViewModel
{
    public function __construct(private readonly mixed $value) {}

    public function getValue()
    {
        return $this->value;
    }
}