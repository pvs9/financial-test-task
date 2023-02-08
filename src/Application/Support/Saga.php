<?php

declare(strict_types=1);

namespace Finance\Application\Support;

class Saga
{
    /**
     * @var callable[]
     */
    private array $compensations = [];

    public function addCompensation(callable $compensation): void
    {
        $this->compensations[] = $compensation;
    }

    public function compensate(): void
    {
        while (!empty($this->compensations)) {
            $compensation = array_pop($this->compensations);
            $compensation();
        }
    }
}
