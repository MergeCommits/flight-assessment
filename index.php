<?php

declare(strict_types=1);

use App\Entities\Airport;
use App\Entities\Airline;
use App\Entities\Flight;
use App\Builders\FlightPathBuilder;

require_once('vendor/autoload.php');

function getArrayFromJsonFile(string $filename, string $key)
{
    $json = file_get_contents(__DIR__ . "/dataset/$filename");
    return json_decode($json, true)[$key];
}

function betterDump(mixed $var)
{
    echo '<pre>' . var_export($var, true) . '</pre>';
}

$airlines = Airline::fromJsonArray(getArrayFromJsonFile('airlines.json', 'airlines'));
$airports = Airport::fromJsonArray(getArrayFromJsonFile('airports.json', 'airports'));
$flights = Flight::fromJsonArray(getArrayFromJsonFile('flights.json', 'flights'), $airlines, $airports);



$flightPaths = FlightPathBuilder::findAllPossibleFlightsBetweenAirports(
    $airports->get('YUL'),
    $airports->get('YVR'),
    new DateTime('2021-01-01')
);

echo($flightPaths->joinFlightNumbers(', '));
