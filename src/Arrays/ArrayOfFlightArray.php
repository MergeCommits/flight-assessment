<?php

declare(strict_types=1);

namespace App\Arrays;

class ArrayOfFlightArray
{
    public $flightArrays = [];

    public function add(FlightArray $flightArray)
    {
        $this->flightArrays[] = $flightArray;
    }

    public function joinFlightNumbers(string $separator): string
    {
        $flightNumbers = [];
        foreach ($this->flightArrays as $flightArray) {
            $flightNumbers[] = "[{$flightArray->joinFlightNumbers($separator)}]";
        }
        return implode($separator, $flightNumbers);
    }

    public function forEach(callable $callback)
    {
        foreach ($this->flightArrays as $flightArray) {
            $callback($flightArray);
        }
    }
}
