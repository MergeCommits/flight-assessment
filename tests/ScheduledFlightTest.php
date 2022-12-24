<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Helpers\Money;
use App\Entities\Airline;
use App\Entities\Airport;
use App\Entities\Flight;
use App\Entities\ScheduledFlight;

final class ScheduledFlightTest extends TestCase
{
    public $flight;

    public $airlines;

    public $airports;

    public function setUp(): void
    {
        $this->airlines = [
            'AC' => new Airline('AC', 'Air Canada'),
            'WS' => new Airline('WS', 'WestJet')
        ];

        $this->airports = [
            'YUL' => new Airport('YUL', 'Montreal', '', '', '', '', '', '', new DateTimeZone('America/Montreal')),
            'YVR' => new Airport('YVR', 'Vancouver', '', '', '', '', '', '', new DateTimeZone('America/Vancouver'))
        ];

        $this->flight = new Flight(
            $this->airlines['AC'],
            '301',
            $this->airports['YUL'],
            new DateTime('jan 1 1970 22:00', new DateTimeZone('America/Montreal')),
            $this->airports['YVR'],
            new DateTime('jan 1 1970 10:00', new DateTimeZone('America/Vancouver')),
            new Money('273.23')
        );
    }

    public function testScheduledFlightConstruction(): void
    {
        $scheduledFlight = new ScheduledFlight($this->flight, new DateTime('2020-02-22'));

        $this->assertEquals($scheduledFlight->flight, $this->flight);

        $this->assertEquals($scheduledFlight->departureDateTime, new DateTime(
            '2020-02-22 ' . $this->flight->departureTime->format('H:i'),
            $this->flight->departureAirport->timezone
        ));
    }

    public function testScheduledFlightCorrectlyAdvancesTheArrivalDateTime(): void
    {
        $scheduledFlight = new ScheduledFlight($this->flight, new DateTime('2020-02-22'));

        $this->assertEquals($scheduledFlight->arrivalDateTime, new DateTime(
            '2020-02-23 ' . $this->flight->arrivalTime->format('H:i'),
            $this->flight->arrivalAirport->timezone
        ));
    }

    public function testScheduledFlightAssignsCorrectArrivalDateWhenTimezone(): void
    {
        $this->flight->arrivalTime = new DateTime('jan 1 1970 20:00', new DateTimeZone('America/Vancouver'));

        $scheduledFlight = new ScheduledFlight($this->flight, new DateTime('2020-02-22'));

        $this->assertEquals($scheduledFlight->arrivalDateTime, new DateTime(
            '2020-02-22 ' . $this->flight->arrivalTime->format('H:i'),
            $this->flight->arrivalAirport->timezone
        ));
    }
}
