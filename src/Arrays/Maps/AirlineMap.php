<?php

declare(strict_types=1);

namespace App\Arrays\Maps;

use App\Entities\Airline;

class AirlineMap
{
    private $airlines = [];

    public function add(Airline $airline)
    {
        $this->airlines[$airline->code] = $airline;
    }

    public function get(string $code): Airline
    {
        return $this->airlines[$code];
    }

    public function has(string $code): bool
    {
        return isset($this->airlines[$code]);
    }

    public function forEach(callable $callback)
    {
        foreach ($this->airlines as $airline) {
            $callback($airline);
        }
    }

    public function toPrimitiveArray(): array
    {
        return $this->airlines;
    }
}
