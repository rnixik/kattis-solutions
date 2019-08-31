<?php

namespace Rnix\Traveller\Tests;

use PHPUnit\Framework\TestCase;
use Rnix\Traveller\Point;

class PointTest extends TestCase
{
    /**
     * @param Point $pointA
     * @param Point $pointB
     * @param float $expectedDistance
     *
     * @dataProvider distanceToDataProvider
     */
    public function testDistanceTo(Point $pointA, Point $pointB, float $expectedDistance)
    {
        $actualDistance = $pointA->distanceTo($pointB);
        $this->assertEqualsWithDelta($expectedDistance, $actualDistance, 0.001);
    }

    public function distanceToDataProvider()
    {
        return [
            [new Point(0, 0), new Point(0, 0), 0.0],
            [new Point(0, 1), new Point(0, 0), 1.0],
            [new Point(1, 0), new Point(0, 0), 1.0],
            [new Point(4, 3), new Point(1, 2), 3.1623],
            [new Point(-4, 3), new Point(1, -2), 7.07107],
        ];
    }
}
