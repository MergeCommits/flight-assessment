<?php declare(strict_types=1);

use App\Entities\Airport;
use App\Entities\Airline;
use App\Entities\Flight;

require_once('vendor/autoload.php');

function getArrayFromJsonFile(string $filename, string $key)
{
    $json = file_get_contents(__DIR__ . "/dataset/$filename");
    return json_decode($json, true)[$key];
}

$airlines = Airline::fromJsonArray(getArrayFromJsonFile('airlines.json', 'airlines'));
$airports = Airport::fromJsonArray(getArrayFromJsonFile('airports.json', 'airports'));
$flights = Flight::fromJsonArray(getArrayFromJsonFile('flights.json', 'flights'), $airlines, $airports);

echo '<pre>' . var_export($flights[0], true) . '</pre>';