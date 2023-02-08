<?php

declare(strict_types=1);

namespace Finance\Application\Support;

interface EntitySorter
{
    /**
     * @param object[] $entities
     * @param string $property
     * @param string $direction
     * @param int $mode
     * @return object[] array
     */
    public function sort(
        array $entities,
        string $property,
        string $direction,
        int $mode = SORT_REGULAR
    ): array;
}
