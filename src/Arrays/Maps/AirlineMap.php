<?php

declare(strict_types=1);

namespace App\Arrays\Maps;

use App\Entities\Airline;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class AirlineMap implements IteratorAggregate
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

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->airlines);
    }
}
