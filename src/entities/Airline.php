<?php

// {
//     "code": "AC",
//     "name": "Air Canada"
// },

namespace App\Entity;

class Airline {
    public $code;
    public $name;

    public function __construct($code, $name) {
        $this->code = $code;
        $this->name = $name;
    }

    public function jsonSerialize() {
        return [
            'code' => $this->code,
            'name' => $this->name
        ];
    }

    public static function fromJson($json) {
        return new Airline(
            $json['code'],
            $json['name']
        );
    }
}