<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Airline;
use App\Entities\Airport;
use App\Helpers\Money;
use DateTime;
use DateTimeZone;
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

    /**
     * @param Airline[] $airlines
     * @param Airport[] $airports
     */
    public static function fromJson($json, $airlines, $airports)
    {
        if (!isset($airlines[$json['airline']])) {
            throw new Exception("Invalid airline code: {$json['airline']}");
        }

        if (!isset($airports[$json['departure_airport']])) {
            throw new Exception("Invalid departure airport code: {$json['departure_airport']}");
        }

        if (!isset($airports[$json['arrival_airport']])) {
            throw new Exception("Invalid arrival airport code: {$json['arrival_airport']}");
        }

        $departureAirport = $airports[$json['departure_airport']];
        $departureTime = DateTime::createFromFormat('H:i', $json['departure_time'], $departureAirport->timezone)
            ->setDate(1970, 1, 1);

        $arrivalAirport = $airports[$json['arrival_airport']];
        $arrivalTime = DateTime::createFromFormat('H:i', $json['arrival_time'], $arrivalAirport->timezone)
            ->setDate(1970, 1, 1);

        return new Flight(
            $airlines[$json['airline']],
            $json['number'],
            $airports[$json['departure_airport']],
            $departureTime,
            $airports[$json['arrival_airport']],
            $arrivalTime,
            new Money($json['price'])
        );
    }

    /**
     * @param Airline[] $airlines
     * @param Airport[] $airports
     */
    public static function fromJsonArray($json, $airlines, $airports)
    {
        $flights = [];
        foreach ($json as $flightJson) {
            $flight = Flight::fromJson($flightJson, $airlines, $airports);
            $flights[] = $flight;
        }

        return $flights;
    }
}
