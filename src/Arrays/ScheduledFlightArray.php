<?php

declare(strict_types=1);

namespace App\Arrays;

use App\Entities\ScheduledFlight;
use App\Helpers\Money;

class ScheduledFlightArray
{
    public $flights = [];

    public function add(ScheduledFlight $flight)
    {
        $this->flights[] = $flight;
    }

    public function get(int $index): ScheduledFlight
    {
        return $this->flights[$index];
    }

    public function count(): int
    {
        return count($this->flights);
    }

    public function first(): ScheduledFlight
    {
        return $this->flights[0];
    }

    public function last(): ScheduledFlight
    {
        return $this->flights[$this->count() - 1];
    }

    public function getTotalPrice(): Money
    {
        $totalPrice = new Money('0.00');
        foreach ($this->flights as $flight) {
            $totalPrice = $totalPrice->add($flight->flight->price);
        }

        return $totalPrice;
    }

    public function getTotalDuration(): int
    {
        $startOfFirstFlight = $this->first()->flight->departureTime;
        $endOfLastFlight = $this->last()->flight->arrivalTime;

        return $endOfLastFlight->getTimestamp() - $startOfFirstFlight->getTimestamp();
    }

    /**
     * @param ScheduledFlightArray[] $flights
     * @return ScheduledFlightArray[]
     */
    public static function sortByPrice(array $flights): array
    {
        usort(
            $flights,
            function (ScheduledFlightArray $a, ScheduledFlightArray $b) {
                $aPrice = $a->getTotalPrice();
                $bPrice = $b->getTotalPrice();

                return $aPrice->spaceshipOperator($bPrice);
            }
        );

        return $flights;
    }

    /**
     * @param ScheduledFlightArray[] $flights
     * @return ScheduledFlightArray[]
     */
    public static function sortByDuration(array $flights): array
    {
        usort(
            $flights,
            function (ScheduledFlightArray $a, ScheduledFlightArray $b) {
                return $a->getTotalDuration() <=> $b->getTotalDuration();
            }
        );

        return $flights;
    }

    /**
     * @param ScheduledFlightArray[] $flights
     * @return ScheduledFlightArray[]
     */
    public static function sortByNumberOfStops(array $flights): array
    {
        usort(
            $flights,
            function (ScheduledFlightArray $a, ScheduledFlightArray $b) {
                return $a->count() <=> $b->count();
            }
        );

        return $flights;
    }

    public function forEach(callable $callback)
    {
        foreach ($this->flights as $flight) {
            $callback($flight);
        }
    }

    public function jsonSerialize()
    {
        $jsonArray = [];
        foreach ($this->flights as $flight) {
            $jsonArray[] = $flight->jsonSerialize();
        }

        return [
            'price' => (string)$this->getTotalPrice(),
            'flights' => $jsonArray
        ];
    }
}
