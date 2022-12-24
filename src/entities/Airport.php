<?php

declare(strict_types=1);

namespace App\Entities;

use DateTimeZone;

class Airport
{
    public $code;
    public $cityCode;
    public $name;
    public $city;
    public $countryCode;
    public $regionCode;
    public $latitude;
    public $longitude;
    public $timezone;

    public function __construct(
        string $code,
        string $cityCode,
        string $name,
        string $city,
        string $countryCode,
        string $regionCode,
        string $latitude,
        string $longitude,
        DateTimeZone $timezone
    ) {
        $this->code = $code;
        $this->cityCode = $cityCode;
        $this->name = $name;
        $this->city = $city;
        $this->countryCode = $countryCode;
        $this->regionCode = $regionCode;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->timezone = $timezone;
    }

    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'city_code' => $this->cityCode,
            'name' => $this->name,
            'city' => $this->city,
            'country_code' => $this->countryCode,
            'region_code' => $this->regionCode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'timezone' => $this->timezone
        ];
    }

    public static function fromJson($json)
    {
        return new Airport(
            $json['code'],
            $json['city_code'],
            $json['name'],
            $json['city'],
            $json['country_code'],
            $json['region_code'],
            (string) $json['latitude'],
            (string) $json['longitude'],
            new DateTimeZone($json['timezone'])
        );
    }

    public static function fromJsonArray($json)
    {
        $airports = [];
        foreach ($json as $airportJson) {
            $airport = Airport::fromJson($airportJson);
            $airports[$airport->code] = $airport;
        }
        return $airports;
    }
}
