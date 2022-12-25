<?php

declare(strict_types=1);

namespace App\Builders;

use App\Arrays\ArrayOfFlightArray;
use App\Arrays\FlightArray;
use App\Arrays\StringArray;
use App\Entities\Airport;
use App\Entities\Flight;
use App\Entities\ScheduledFlight;
use DateTime;

final class FlightPathBuilder
{
    public static function findAllPossibleFlightsBetweenAirports(
        Airport $origin,
        Airport $destination,
        DateTime $departureDate
    ): ArrayOfFlightArray {
        $visitedAirports = new StringArray();
        $allValidFlightPaths = new ArrayOfFlightArray();

        self::DFS($origin, $destination, $visitedAirports, new FlightArray(), $allValidFlightPaths);

        return $allValidFlightPaths;
    }

    private static function DFS(
        Airport $origin,
        Airport $destination,
        StringArray $visitedAirports,
        FlightArray $currentFlightPath,
        ArrayOfFlightArray $allValidFlightPaths
    ): void {
        $visited = $visitedAirports->clone();
        $visited->add($origin->code);

        if ($origin->code == $destination->code) {
            $allValidFlightPaths->add($currentFlightPath);
            return;
        }

        $origin->getFlights()->forEach(
            function (Flight $flight) use (
                $destination,
                $visited,
                $currentFlightPath,
                $allValidFlightPaths
            ) {
                if (!$visited->contains($flight->arrivalAirport->code)) {
                    $newFlightPath = $currentFlightPath->clone();
                    $newFlightPath->add($flight);
                    self::DFS($flight->arrivalAirport, $destination, $visited, $newFlightPath, $allValidFlightPaths);
                }
            }
        );
    }

    private static function validateFlightPath(FlightArray $flightPath, DateTime $departureDate): array
    {
        $scheduledFlights = [];
        // combine date of departure with the first flight's departure time
        $currentDepartureDateTime = new DateTime(
            $departureDate->format('Y-m-d') . ' ' . $flightPath->get(0)->departureTime->format('H:i'),
            $flightPath->get(0)->departureAirport->timezone
        );
        $flightPath->forEach(
            function (Flight $flight) use (&$scheduledFlights, &$currentDepartureDateTime) {
                $scheduled = new ScheduledFlight($flight, $currentDepartureDateTime);
                $scheduledFlights[] = $scheduled;
                $currentDepartureDateTime = $scheduled->arrivalDateTime;
            }
        );
    }
}
