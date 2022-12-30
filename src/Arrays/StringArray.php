<?php

declare(strict_types=1);

namespace App\Arrays;

class StringArray
{
    private $strings = [];

    public function add(string $string)
    {
        $this->strings[] = $string;
    }

    public function contains(string $string): bool
    {
        return in_array($string, $this->strings);
    }

    public function forEach(callable $callback)
    {
        foreach ($this->strings as $string) {
            $callback($string);
        }
    }

    public function clone(): StringArray
    {
        $clone = new StringArray();
        $clone->strings = $this->strings;
        return $clone;
    }
}
