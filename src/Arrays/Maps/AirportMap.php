<?php

declare(strict_types=1);

namespace App\Arrays\Maps;

use App\Entities\Airport;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class AirportMap implements IteratorAggregate
{
    private $airports = [];

    public function add(Airport $airport)
    {
        $this->airports[$airport->code] = $airport;
    }

    public function get(string $code): Airport
    {
        return $this->airports[$code];
    }

    public function has(string $code): bool
    {
        return isset($this->airports[$code]);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->airports);
    }
}
