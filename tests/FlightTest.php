<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Helpers\Money;
use App\Entities\Airline;
use App\Entities\Airport;
use App\Entities\Flight;

final class FlightTest extends TestCase
{
    public $flightJson;

    public $airlines;

    public $airports;

    public function setUp(): void
    {
        $this->flightJson = [
            'airline' => 'AC',
            'number' => '301',
            'departure_airport' => 'YUL',
            'departure_time' => '22:00',
            'arrival_airport' => 'YVR',
            'arrival_time' => '20:00',
            'price' => '273.23'
        ];

        $this->airlines = [
            'AC' => new Airline('AC', 'Air Canada'),
            'WS' => new Airline('WS', 'WestJet')
        ];

        $this->airports = [
            'YUL' => new Airport('YUL', 'Montreal', '', '', '', '', '', '', new DateTimeZone('America/Montreal')),
            'YVR' => new Airport('YVR', 'Vancouver', '', '', '', '', '', '', new DateTimeZone('America/Vancouver'))
        ];
    }

    public function testFlightFromJson(): void
    {
        $flight = Flight::fromJson($this->flightJson, $this->airlines, $this->airports);

        $this->assertEquals($flight->airline, $this->airlines['AC']);
        $this->assertEquals($flight->number, '301');
        $this->assertEquals($flight->departureAirport, $this->airports['YUL']);
        $this->assertEquals($flight->departureTime, new DateTime('jan 1 1970 22:00', new DateTimeZone('America/Montreal')));
        $this->assertEquals($flight->arrivalAirport, $this->airports['YVR']);
        $this->assertEquals($flight->arrivalTime, new DateTime('jan 1 1970 20:00', new DateTimeZone('America/Vancouver')));
        $this->assertEquals($flight->price, new Money('273.23'));
    }

    public function testFlightFromJsonWithInvalidAirline(): void
    {
        $this->flightJson['airline'] = 'ZZ';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid airline code: ZZ');

        Flight::fromJson($this->flightJson, $this->airlines, $this->airports);
    }

    public function testFlightFromJsonWithInvalidDepartureAirport(): void
    {
        $this->flightJson['departure_airport'] = 'ZZ';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid departure airport code: ZZ');

        Flight::fromJson($this->flightJson, $this->airlines, $this->airports);
    }

    public function testFlightFromJsonWithInvalidArrivalAirport(): void
    {
        $this->flightJson['arrival_airport'] = 'ZZ';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid arrival airport code: ZZ');

        Flight::fromJson($this->flightJson, $this->airlines, $this->airports);
    }
}
