<?php

declare(strict_types=1);

namespace App\Arrays;

use App\Entities\ScheduledFlight;

class ScheduledFlightArray
{
    public $flights = [];

    public function add(ScheduledFlight $flight)
    {
        $this->flights[] = $flight;
    }

    public function get(int $index): ScheduledFlight
    {
        return $this->flights[$index];
    }

    public function count(): int
    {
        return count($this->flights);
    }

    public function last(): ScheduledFlight
    {
        return $this->flights[count($this->flights) - 1];
    }

    public function forEach(callable $callback)
    {
        foreach ($this->flights as $flight) {
            $callback($flight);
        }
    }
}
