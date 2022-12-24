<?php

declare(strict_types=1);

namespace App;

class FlightPlan
{
    /**
     * @var mixed
     */
    public $flights = [];

    public function __construct($flights)
    {
        $this->flights = $flights;
    }

    public function jsonSerialize()
    {
        return [
            'flights' => $this->flights
        ];
    }

    public static function fromJson($json, $airlines, $airports)
    {
        $flights = [];
        foreach ($json as $key => $flightJson) {
            $flight = Flight::fromJson($flightJson, $airlines, $airports);
            $flights[] = $flight;
        }
        return new FlightPlan($flights);
    }
}
