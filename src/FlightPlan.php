<?php

declare(strict_types=1);

namespace App;

use App\Arrays\FlightArrayArray;
use App\Arrays\Maps\AirlineMap;
use App\Arrays\Maps\AirportMap;
use App\Arrays\Maps\AirportStack;
use App\Entities\Airport;
use App\Entities\Flight;
use App\Entities\ScheduledFlight;
use DateTime;
use SplStack;

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
}
