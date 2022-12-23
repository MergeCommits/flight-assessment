<?php

namespace App\Entities;

class Airline
{
    public $code;
    public $name;

    public function __construct($code, $name)
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

    public static function fromJsonArray($json)
    {
        $airlines = [];
        foreach ($json as $key => $airlineJson) {
            $airline = Airline::fromJson($airlineJson);
            $airlines[$airline->code] = $airline;
        }
        return $airlines;
    }
}
