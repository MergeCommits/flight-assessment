<?php

declare(strict_types=1);

use App\Entities\Airport;
use App\Entities\Airline;
use App\Entities\Flight;
use App\Arrays\StringArray;

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

// $departureDate = new DateTime('2020-02-22');
// $scheduleFlight = new ScheduledFlight($flights->get(0), $departureDate);
// betterDump($scheduleFlight);

/**
 * finds all possible flights between two airports
 * returns an array of strings
 * each string contains comma separated flight numbers
 */
function findAllPossibleFlightsBetweenAirports(
    Airport $origin,
    Airport $destination
): array {
    $visitedAirports = new StringArray();
    $flightPaths = [];

    DFS($origin, $destination, $visitedAirports, "", $flightPaths);

    return $flightPaths;
}

function DFS(Airport $origin, Airport $destination, StringArray $visitedAirports, string $flightNumbers, array &$flightPaths)
{
    $visited = $visitedAirports->clone();
    $visited->add($origin->code);

    echo "Visiting {$origin->code}" . '<br>';
    echo "Visited: " . implode(',', $visited->toArray()) . '<br>';
    echo "Flight Paths: " . implode(',', $flightPaths) . '<br>';
    echo "At destination: " . ($origin->code == $destination->code ? 'true' : 'false') . '<br>';
    // new line

    if ($origin->code == $destination->code) {
        // $flightPaths[] = implode(',', $visited->toArray());
        echo "Found a path: " . $flightNumbers . '<br>';
        // echo "Current flight paths: " . implode(',', $flightPaths) . '<br>';
        echo '----------' . '<br>';
        return;
    }

    echo '----------' . '<br>';

    $origin->getFlights()->forEach(function (Flight $flight) use ($origin, $destination, $visited, $flightNumbers, $flightPaths) {
        if (!$visited->contains($flight->arrivalAirport->code)) {
            echo "Going {$origin->code} --> {$flight->arrivalAirport->code}" . '<br>';
            echo '----------' . '<br>';
            $flightAppend = $flightNumbers . $flight->number . ',';
            DFS($flight->arrivalAirport, $destination, $visited, $flightAppend, $flightPaths);
        }
    });
}

$flightPaths = findAllPossibleFlightsBetweenAirports(
    $airports->get('YUL'),
    $airports->get('YVR')
);

betterDump($flightPaths);
