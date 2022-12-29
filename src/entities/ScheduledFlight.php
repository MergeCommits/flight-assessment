<?php

declare(strict_types=1);

namespace App\Entities;

use DateTime;
use DateTimeZone;

class ScheduledFlight
{
    public $flight;

    public $departureDateTime;
    public $arrivalDateTime;

    public function __construct(Flight $flight, DateTime $arrivalDateTime)
    {
        $this->flight = $flight;

        $this->departureDateTime = self::computeDepartureDateTime(
            $arrivalDateTime,
            $flight->departureTime,
            $flight->departureAirport->timezone
        );

        $this->arrivalDateTime = self::computeArrivalDateTime(
            $this->departureDateTime,
            $flight->arrivalTime,
            $flight->arrivalAirport->timezone
        );
    }

    private static function computeDepartureDateTime(
        DateTime $arrivalDateTime,
        DateTime $departureTime,
        DateTimeZone $departureTimezone
    ): DateTime {
        $candidateDepartureDateTime = new DateTime(
            $arrivalDateTime->format('Y-m-d') . ' ' . $departureTime->format('H:i'),
            $departureTimezone
        );

        if ($candidateDepartureDateTime <= $arrivalDateTime) {
            $candidateDepartureDateTime->modify('+1 day');
        }

        return $candidateDepartureDateTime;
    }

    private static function computeArrivalDateTime(DateTime $departureDate, DateTime $arrivalTime, DateTimeZone $arrivalTimezone)
    {
        $candidateArrivalDateTime = new DateTime(
            $departureDate->format('Y-m-d') . ' ' . $arrivalTime->format('H:i'),
            $arrivalTimezone
        );

        if ($candidateArrivalDateTime <= $departureDate) {
            $candidateArrivalDateTime->modify('+1 day');
        }

        return $candidateArrivalDateTime;
    }

    public function jsonSerialize(): array
    {
        $flightJson = $this->flight->jsonSerialize();
        return [
            'airline' => $flightJson['airline'],
            'number' => $flightJson['number'],
            'departure_airport' => $flightJson['departure_airport'],
            'departure_datetime' => $this->departureDateTime->format('Y-m-d H:i'),
            'arrival_airport' => $flightJson['arrival_airport'],
            'arrival_datetime' => $this->arrivalDateTime->format('Y-m-d H:i'),
            'price' => (string)$flightJson['price']
        ];
    }
}
