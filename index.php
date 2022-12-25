<?php

declare(strict_types=1);

use App\Entities\Airport;
use App\Entities\Airline;
use App\Entities\Flight;
use App\Arrays\StringArray;
use App\Arrays\FlightArray;
use App\Arrays\ArrayOfFlightArray;

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
): ArrayOfFlightArray {
    $visitedAirports = new StringArray();
    $allValidFlightPaths = new ArrayOfFlightArray();

    DFS($origin, $destination, $visitedAirports, new FlightArray(), $allValidFlightPaths);

    return $allValidFlightPaths;
}

function DFS(
    Airport $origin,
    Airport $destination,
    StringArray $visitedAirports,
    FlightArray $currentFlightPath,
    ArrayOfFlightArray $allValidFlightPaths
): void {
    $visited = $visitedAirports->clone();
    $visited->add($origin->code);

    if ($origin->code == $destination->code) {
        $allValidFlightPaths->add($currentFlightPath);
        echo "Found a path: " . $currentFlightPath->joinFlightNumbers(", ") . '<br>';
        echo '----------' . '<br>';
        return;
    }

    $origin->getFlights()->forEach(
        function (Flight $flight) use (
            $destination,
            $visited,
            $currentFlightPath,
            $allValidFlightPaths
        ) {
            if (!$visited->contains($flight->arrivalAirport->code)) {
                $newFlightPath = $currentFlightPath->clone();
                $newFlightPath->add($flight);
                DFS($flight->arrivalAirport, $destination, $visited, $newFlightPath, $allValidFlightPaths);
            }
        }
    );
}

$flightPaths = findAllPossibleFlightsBetweenAirports(
    $airports->get('YUL'),
    $airports->get('YVR')
);

echo($flightPaths->joinFlightNumbers(', '));
