<?php

class Money {
    private string $amount;

    public function __construct(string $price) {
        $this->amount = $price;
    }

    public function add(Money $other) {
        return new Money(bcadd($this->amount, $other->amount, 2));
    }

    public function __toString() {
        return $this->amount;
    }
}