<?php

declare(strict_types=1);

namespace App\Helpers;

class Money
{
    private string $amount;

    public function __construct(string $price)
    {
        $this->amount = $price;
    }

    public function add(Money $other)
    {
        return new Money(bcadd($this->amount, $other->amount, 2));
    }

    public function __toString()
    {
        return $this->amount;
    }

    public function spaceshipOperator(Money $other)
    {
        return bccomp($this->amount, $other->amount, 2);
    }
}
