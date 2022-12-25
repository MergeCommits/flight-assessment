<?php

declare(strict_types=1);

namespace App\Arrays\Maps;

use App\Entities\Airport;

class AirportMap
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

    public function forEach(callable $callback)
    {
        foreach ($this->airports as $airport) {
            $callback($airport);
        }
    }

    public function toArray(): array
    {
        return array_values($this->airports);
    }
}
