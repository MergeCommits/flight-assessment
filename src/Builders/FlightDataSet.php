<?php

declare(strict_types=1);

namespace App\Builders;

use App\Entities\Airline;
use App\Entities\Airport;
use App\Entities\Flight;

final class FlightDataSet
{
    private $airlines;
    public function getAirlines()
    {
        return $this->airlines;
    }
    private $airports;
    public function getAirports()
    {
        return $this->airports;
    }
    private $flights;
    public function getFlights()
    {
        return $this->flights;
    }

    public function __construct()
    {
        $this->airlines = Airline::fromJsonArray($this->getArrayFromJsonFile('airlines.json', 'airlines'));
        $this->airports = Airport::fromJsonArray($this->getArrayFromJsonFile('airports.json', 'airports'));
        $this->flights = Flight::fromJsonArray($this->getArrayFromJsonFile('flights.json', 'flights'), $this->airlines, $this->airports);
    }

    private function getArrayFromJsonFile(string $filename, string $key)
    {
        $json = file_get_contents(__DIR__ . "/dataset/$filename");
        return json_decode($json, true)[$key];
    }
}
