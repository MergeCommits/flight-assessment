<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Helpers\Money;

final class MoneyTest extends TestCase
{
    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            '100.00',
            new Money('100.00')
        );
    }

    public function testCanBeAdded(): void
    {
        $this->assertEquals(
            '200.00',
            (new Money('100.00'))->add(new Money('100.00'))
        );
    }

    public function testCanBeAddedMultipleTimes(): void
    {
        $this->assertEquals(
            '300.00',
            (new Money('100.00'))->add(new Money('100.00'))->add(new Money('100.00'))
        );
    }
}
