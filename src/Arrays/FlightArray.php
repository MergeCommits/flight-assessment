<?php

declare(strict_types=1);

namespace App\Arrays;

use App\Entities\Flight;

class FlightArray
{
    public $flights = [];

    public function add(Flight $flight)
    {
        $this->flights[] = $flight;
    }

    public function get(int $index): Flight
    {
        return $this->flights[$index];
    }

    public function forEach(callable $callback)
    {
        foreach ($this->flights as $flight) {
            $callback($flight);
        }
    }
}
