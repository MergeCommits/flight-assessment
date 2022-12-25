<?php

declare(strict_types=1);

namespace App\Entities;

use App\Arrays\FlightArray;
use App\Arrays\Maps\AirlineMap;
use App\Arrays\Maps\AirportMap;
use App\Entities\Airline;
use App\Entities\Airport;
use App\Helpers\Money;
use DateTime;
use Exception;

class Flight
{
    public $airline;
    public $number;
    public $departureAirport;
    public $departureTime;
    public $arrivalAirport;
    public $arrivalTime;
    public $price;

    public function __construct(
        Airline $airline,
        string $number,
        Airport $departureAirport,
        DateTime $departureTime,
        Airport $arrivalAirport,
        DateTime $arrivalTime,
        Money $price
    ) {
        $this->airline = $airline;
        $this->number = $number;
        $this->departureAirport = $departureAirport;
        $this->departureTime = $departureTime;
        $this->arrivalAirport = $arrivalAirport;
        $this->arrivalTime = $arrivalTime;
        $this->price = $price;

        $departureAirport->addFlight($this);
        $arrivalAirport->addFlight($this);
    }

    public function jsonSerialize()
    {
        return [
            'airline' => $this->airline,
            'number' => $this->number,
            'departure_airport' => $this->departureAirport,
            'departure_time' => $this->departureTime,
            'arrival_airport' => $this->arrivalAirport,
            'arrival_time' => $this->arrivalTime,
            'price' => $this->price
        ];
    }

    public static function fromJson($json, AirlineMap $airlines, AirportMap $airports)
    {
        if (!$airlines->has($json['airline'])) {
            throw new Exception("Invalid airline code: {$json['airline']}");
        }

        if (!$airports->has($json['departure_airport'])) {
            throw new Exception("Invalid departure airport code: {$json['departure_airport']}");
        }

        if (!$airports->has($json['arrival_airport'])) {
            throw new Exception("Invalid arrival airport code: {$json['arrival_airport']}");
        }

        $departureAirport = $airports->get($json['departure_airport']);
        $departureTime = DateTime::createFromFormat('H:i', $json['departure_time'], $departureAirport->timezone)
            ->setDate(1970, 1, 1);

        $arrivalAirport = $airports->get($json['arrival_airport']);
        $arrivalTime = DateTime::createFromFormat('H:i', $json['arrival_time'], $arrivalAirport->timezone)
            ->setDate(1970, 1, 1);

        return new Flight(
            $airlines->get($json['airline']),
            $json['number'],
            $airports->get($json['departure_airport']),
            $departureTime,
            $airports->get($json['arrival_airport']),
            $arrivalTime,
            new Money($json['price'])
        );
    }

    public static function fromJsonArray($json, AirlineMap $airlines, AirportMap $airports): FlightArray
    {
        $flights = new FlightArray();
        foreach ($json as $key => $flightJson) {
            $flight = Flight::fromJson($flightJson, $airlines, $airports);
            $flights->add($flight);
        }
        return $flights;
    }
}
