<?php

declare(strict_types=1);

namespace App\Entities;

use App\Arrays\Maps\AirlineMap;

class Airline
{
    public $code;
    public $name;

    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'name' => $this->name
        ];
    }

    public static function fromJson($json)
    {
        return new Airline(
            $json['code'],
            $json['name']
        );
    }

    public static function fromJsonArray($json): AirlineMap
    {
        $airlines = new AirlineMap();
        foreach ($json as $key => $airlineJson) {
            $airline = Airline::fromJson($airlineJson);
            $airlines->add($airline);
        }
        return $airlines;
    }
}
