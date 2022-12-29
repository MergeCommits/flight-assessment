<?php

declare(strict_types=1);

namespace App\Builders;

use App\Arrays\ArrayOfFlightArray;
use App\Arrays\FlightArray;
use App\Arrays\ScheduledFlightArray;
use App\Arrays\StringArray;
use App\Entities\Airport;
use App\Entities\Flight;
use App\Entities\ScheduledFlight;
use DateTime;

final class FlightPathBuilder
{
    public static function findAllTrips(
        Airport $origin,
        Airport $destination,
        DateTime $departureDate,
        bool $returnTrip = false,
        DateTime $returnDate = null
    ) {
        $allPossibleFlights = self::findAllPossibleFlightsBetweenAirports(
            $origin,
            $destination,
            $departureDate,
            $returnTrip,
            $returnDate
        );

        return $allPossibleFlights;
    }

    /**
     * @return ScheduledFlightArray[]
     */
    private static function findAllPossibleFlightsBetweenAirports(
        Airport $origin,
        Airport $destination,
        DateTime $departureDate,
        bool $returnTrip = false,
        DateTime $returnDate = null
    ): array {
        $allValidFlightPaths = new ArrayOfFlightArray();
        self::DFS($origin, $destination, new StringArray(), new FlightArray(), $allValidFlightPaths);

        if ($returnTrip) {
            $allValidReturnPaths = new ArrayOfFlightArray();
            self::DFS($destination, $origin, new StringArray(), new FlightArray(), $allValidReturnPaths);

            $allFlightsCombinedWithReturnTrips = new ArrayOfFlightArray();

            $allValidFlightPaths->forEach(
                function (FlightArray $flightPath) use (
                    $allValidReturnPaths,
                    $allFlightsCombinedWithReturnTrips
                ) {
                    $allValidReturnPaths->forEach(
                        function (FlightArray $returnPath) use (
                            $flightPath,
                            $allFlightsCombinedWithReturnTrips
                        ) {
                            $allFlightsCombinedWithReturnTrips->add(
                                $flightPath->concat($returnPath)
                            );
                        }
                    );
                }
            );

            $allValidFlightPaths = $allFlightsCombinedWithReturnTrips;
        }

        $amongUs = [];
        $allValidFlightPaths->forEach(
            function (FlightArray $flightPath) use (&$amongUs, $departureDate, $returnDate) {
                $candidate = self::validateFlightPath($flightPath, $departureDate, $returnDate);
                if ($candidate !== null) {
                    $amongUs[] = $candidate;
                }
            }
        );

        return $amongUs;
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

    private static function convertFlightsToScheduledFlights(
        FlightArray $flightPath,
        DateTime $departureDate
    ): ScheduledFlightArray {
        $scheduledFlights = new ScheduledFlightArray();
        $currentDepartureDateTime = new DateTime(
            $departureDate->format('Y-m-d') . ' ' . $flightPath->get(0)->departureTime->format('H:i'),
            $flightPath->get(0)->departureAirport->timezone
        );
        $currentDepartureDateTime->modify('-1 minute');

        $flightPath->forEach(
            function (Flight $flight) use (&$scheduledFlights, &$currentDepartureDateTime) {
                $scheduled = new ScheduledFlight($flight, $currentDepartureDateTime);
                $scheduledFlights->add($scheduled);
                $currentDepartureDateTime = $scheduled->arrivalDateTime;
            }
        );

        return $scheduledFlights;
    }

    private static function validateFlightPath(
        FlightArray $flightPath,
        DateTime $departureDate,
        DateTime $returnDate = null
    ): ScheduledFlightArray | null {
        $scheduledFlights = self::convertFlightsToScheduledFlights($flightPath, $departureDate);

        if ($returnDate) {
            $lastFlight = $scheduledFlights->get($scheduledFlights->count() - 1);
            $lastFlightArrivalDateTime = $lastFlight->arrivalDateTime;

            $returnDate = new DateTime(
                $returnDate->format('Y-m-d') . ' ' . $lastFlightArrivalDateTime->format('H:i'),
                $lastFlightArrivalDateTime->getTimezone()
            );

            if ($returnDate < $lastFlightArrivalDateTime) {
                return null;
            }
        }

        return $scheduledFlights;
    }
}
