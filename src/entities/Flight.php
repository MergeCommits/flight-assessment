<?php

namespace App\Entities;

use App\Entities\Airline;
use App\Entities\Airport;
use App\Helpers\Money;
use Exception;

class Flight
{
    public Airline $airline;
    public $number;
    public Airport $departure_airport;
    public $departure_time;
    public Airport $arrival_airport;
    public $arrival_time;
    public Money $price;

    public function __construct(
        $airline,
        $number,
        Airport $departure_airport,
        $departure_time,
        Airport $arrival_airport,
        $arrival_time,
        Money $price
    ) {
        $this->airline = $airline;
        $this->number = $number;
        $this->departure_airport = $departure_airport;
        $this->departure_time = $departure_time;
        $this->arrival_airport = $arrival_airport;
        $this->arrival_time = $arrival_time;
        $this->price = $price;
    }

    public function jsonSerialize()
    {
        return [
            'airline' => $this->airline,
            'number' => $this->number,
            'departure_airport' => $this->departure_airport,
            'departure_time' => $this->departure_time,
            'arrival_airport' => $this->arrival_airport,
            'arrival_time' => $this->arrival_time,
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
            throw new Exception("Airline {$json['airline']} not found");
        }

        return new Flight(
            $airlines[$json['airline']],
            $json['number'],
            $airports[$json['departure_airport']],
            $json['departure_time'],
            $airports[$json['arrival_airport']],
            $json['arrival_time'],
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
