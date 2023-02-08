<?php

namespace Finance\Application\Support;

class NativeEntitySorter implements EntitySorter
{
    /**
     * @param object[] $entities
     * @param string $property
     * @param string $direction
     * @param int $mode
     * @return object[]
     */
    public function sort(
        array $entities,
        string $property,
        string $direction,
        int $mode = SORT_REGULAR
    ): array {
        $propertyMethod = $this->getPropertyMethod($property);
        $isDesc = $direction === 'desc';

        if ($mode === SORT_STRING) {
            return $this->sortStrings($entities, $propertyMethod, $isDesc);
        }

        return $this->sortRegular($entities, $propertyMethod, $isDesc);
    }

    private function getPropertyMethod(string $property): string
    {
        return sprintf(
            'get%s',
            mb_strtoupper(
                mb_substr($property, 0, 1, 'UTF-8'),
                'UTF-8'
            ) . mb_substr($property, 1, null, 'UTF-8')
        );
    }

    /**
     * @param object[] $entities
     * @param string $propertyMethod
     * @param bool $isDesc
     * @return object[]
     */
    private function sortRegular(
        array $entities,
        string $propertyMethod,
        bool $isDesc = false
    ): array {
        $sort = static fn($a, $b) => $a->{$propertyMethod}() <=> $b->{$propertyMethod}();

        if ($isDesc) {
            $sort = static fn($a, $b) => $b->{$propertyMethod}() <=> $a->{$propertyMethod}();
        }

        usort($entities, $sort);

        return $entities;
    }

    /**
     * @param object[] $entities
     * @param string $propertyMethod
     * @param bool $isDesc
     * @return object[]
     */
    private function sortStrings(
        array $entities,
        string $propertyMethod,
        bool $isDesc = false
    ): array {
        $sort = static fn($a, $b) => strcmp($a->{$propertyMethod}(), $b->{$propertyMethod}());

        if ($isDesc) {
            $sort = static fn($a, $b) => strcmp($b->{$propertyMethod}(), $a->{$propertyMethod}());
        }

        usort($entities, $sort);

        return $entities;
    }
}
