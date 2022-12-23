<?php

use App\Entity\Airline;

$airlines = [];
$airlinesJson = json_decode(file_get_contents('src/dataset/airlines.json'), true);
foreach ($airlinesJson as $airlineJson) {
    $airline = Airline::fromJson($airlineJson);
    $airlines[$airline->code] = $airline;
}

// pull airports from dataset
$airports = [];
$airportsJson = json_decode(file_get_contents('src/dataset/airports.json'), true);
foreach ($airportsJson as $airportJson) {
    $airport = Airport::fromJson($airportJson);
    $airports[$airport->code] = $airport;
}

// pull flights from dataset
$flights = [];
$flightsJson = json_decode(file_get_contents('src/dataset/flights.json'), true);
foreach ($flightsJson as $flightJson) {
    $flight = Flight::fromJson($flightJson, $airlines);
    $flights[] = $flight;
}