<?php

declare(strict_types=1);

namespace App\Entities;

class Airport
{
    public $code;
    public $city_code;
    public $name;
    public $city;
    public $country_code;
    public $region_code;
    public $latitude;
    public $longitude;
    public $timezone;

    public function __construct($code, $city_code, $name, $city, $country_code, $region_code, $latitude, $longitude, $timezone)
    {
        $this->code = $code;
        $this->city_code = $city_code;
        $this->name = $name;
        $this->city = $city;
        $this->country_code = $country_code;
        $this->region_code = $region_code;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->timezone = $timezone;
    }

    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'city_code' => $this->city_code,
            'name' => $this->name,
            'city' => $this->city,
            'country_code' => $this->country_code,
            'region_code' => $this->region_code,
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
            $json['latitude'],
            $json['longitude'],
            $json['timezone']
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
