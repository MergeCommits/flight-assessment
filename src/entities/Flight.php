<?php

use App\Entity\Airline;
use App\Entity\Airport;

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
    )
    {
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
     */
    public static function fromJson($json, $airlines)
    {
        if (!isset($airlines[$json['airline']])) {
            throw new Exception("Airline {$json['airline']} not found");
        }

        return new Flight(
            $airlines[$json['airline']],
            $json['number'],
            $json['departure_airport'],
            $json['departure_time'],
            $json['arrival_airport'],
            $json['arrival_time'],
            new Money($json['price'])
        );
    }
}
