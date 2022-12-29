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

    public function count(): int
    {
        return count($this->flights);
    }

    public function concat(FlightArray $other): FlightArray
    {
        $clone = $this->clone();
        $clone->flights = array_merge($clone->flights, $other->flights);
        return $clone;
    }

    public function forEach(callable $callback)
    {
        foreach ($this->flights as $flight) {
            $callback($flight);
        }
    }

    public function clone(): FlightArray
    {
        $clone = new FlightArray();
        $clone->flights = $this->flights;
        return $clone;
    }
}
