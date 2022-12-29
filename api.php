<?php

declare(strict_types=1);

use App\Builders\FlightDataSet;
use App\Builders\FlightPathBuilder;
use App\Entities\Airline;
use App\Entities\Airport;
use App\Entities\Flight;

require_once('vendor/autoload.php');

function getArrayFromJsonFile(string $filename, string $key)
{
    $json = file_get_contents(__DIR__ . "/dataset/$filename");
    return json_decode($json, true)[$key];
}

function convertToDateTime(string $date, DateTimeZone $timezone): DateTime
{
    return DateTime::createFromFormat('Y-m-d', $date, $timezone);
}

$airlines = Airline::fromJsonArray(getArrayFromJsonFile('airlines.json', 'airlines'));
$airports = Airport::fromJsonArray(getArrayFromJsonFile('airports.json', 'airports'));
$flights = Flight::fromJsonArray(getArrayFromJsonFile('flights.json', 'flights'), $airlines, $airports);

$originAirportCode = $_GET['departure_airport'];
$destinationAirportCode = $_GET['arrival_airport'];

$originAirportTimezone = $airports->get($originAirportCode)->timezone;

$departureDate = convertToDateTime($_GET['departure_date'], $originAirportTimezone);

$roundTrip = $_GET['trip_type'] == 'round-trip' ? true : false;
$returnDate = null;

if ($roundTrip) {
    $returnDateString = $_GET['return_date'] ?? null;
    if ($returnDateString !== null) {
        $returnDate = convertToDateTime($returnDateString, $originAirportTimezone);
    }
}

$flightPaths = FlightPathBuilder::findAllTrips(
    $airports->get($originAirportCode),
    $airports->get($destinationAirportCode),
    $departureDate,
    $roundTrip,
    $returnDate
);

// return api response
header('Content-Type: application/json');
echo json_encode($flightPaths);
