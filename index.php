<?php

require_once('vendor/autoload.php');

use App\Entities\Airline;

function getArrayFromJsonFile(string $filename, string $key)
{
    $json = file_get_contents(__DIR__ . "/dataset/$filename");
    return json_decode($json, true)[$key];
}

$airlines = Airline::fromJsonArray(getArrayFromJsonFile('airlines.json', 'airlines'));

// $airportsJson = json_decode(file_get_contents('/dataset/airports.json'), true);
// $airports = Airport::fromJsonArray($airportsJson);

// $flightsJson = json_decode(file_get_contents('/dataset/flights.json'), true);
// $flights = Flight::fromJsonArray($flightsJson, $airlines, $airports);

// $airline = new Airline('AC', 'Air Canada');
echo($airlines['AC']->name);