<?php

declare(strict_types=1);

namespace App;

use App\Entities\Airport;
use App\Entities\Flight;
use App\Entities\ScheduledFlight;
use DateTime;

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
