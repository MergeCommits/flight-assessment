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

    public function __construct(Flight $flight, DateTime $departureDate)
    {
        $this->flight = $flight;

        $this->departureDateTime = new DateTime(
            $departureDate->format('Y-m-d') . ' ' . $flight->departureTime->format('H:i'),
            $flight->departureAirport->timezone
        );

        $this->arrivalDateTime = self::computeArrivalDate(
            $this->departureDateTime,
            $flight->arrivalTime,
            $flight->arrivalAirport->timezone
        );
    }

    private static function computeArrivalDate(DateTime $departureDate, DateTime $arrivalTime, DateTimeZone $arrivalTimezone)
    {
        $arrivalDate = new DateTime(
            $departureDate->format('Y-m-d') . ' ' . $arrivalTime->format('H:i'),
            $arrivalTimezone
        );

        if ($arrivalDate <= $departureDate) {
            echo "{$arrivalTime->format('Y-m-d H:i')} <= {$departureDate->format('Y-m-d H:i')}" . PHP_EOL;
            $arrivalDate->modify('+1 day');
        }

        return $arrivalDate;
    }
}
