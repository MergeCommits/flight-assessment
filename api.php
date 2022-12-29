<?php

declare(strict_types=1);

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

if (!isset($_GET['departure_airport']) || !isset($_GET['arrival_airport'])) {
    http_response_code(400);
    echo 'departure_airport and arrival_airport are required';
    exit;
}

if ($_GET['departure_airport'] === $_GET['arrival_airport']) {
    http_response_code(400);
    echo 'departure_airport and arrival_airport must be different';
    exit;
}

if (!isset($_GET['departure_date'])) {
    http_response_code(400);
    echo 'departure_date is required';
    exit;
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

$jsonArray = [];
foreach ($flightPaths as $flightPath) {
    $jsonArray[] = $flightPath->jsonSerialize();
}

header('Content-Type: application/json');
echo json_encode([
    'trips' => $jsonArray
]);
