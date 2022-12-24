<?php

declare(strict_types=1);

namespace App\Arrays;

use App\Entities\Flight;
use ArrayIterator;
use Traversable;

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

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->flights);
    }
}
