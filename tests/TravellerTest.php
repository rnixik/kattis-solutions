<?php

namespace Rnix\Traveller\Tests;

use PHPUnit\Framework\TestCase;
use Rnix\Traveller\Point;
use Rnix\Traveller\Traveller;

class TravellerTest extends TestCase
{
    /*
    public function testGetFinalDestination()
    {
        $traveller = new Traveller();
        $start = new Point(87.342, 34.30);
        $commands = [
            'start 0',
            'walk 10.0',
        ];
        $expectedPoint = new Point(1.1, 3.2);
        $actualPoint = $traveller->getFinalDestination($start, $commands);
        $this->assertEquals($expectedPoint, $actualPoint);
    }

    public function getFinalDestinationDataProvider()
    {
        return [
            [
                97.342,
                34.30,
                87.342,
                34.30,
                ['start 0', 'walk 10.0',],
            ],
            [
                87.342,
                44.30,
                87.342,
                34.30,
                ['start 90', 'walk 10.0',],
            ],
            [
                87.342 + 0.137073546,
                34.30 + 0.137073546,
                87.342,
                34.30,
                ['start 45', 'walk 10.0',],
            ],
        ];
    }
    */

    /**
     * @param float $degrees
     * @param float $expected
     * @dataProvider getRadiansFromDegreesDataProvider
     */
    public function testGetRadiansFromDegrees(float $degrees, float $expected)
    {
        $actual = Traveller::getRadiansFromDegrees($degrees);
        $this->assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function getRadiansFromDegreesDataProvider()
    {
        return [
            [90, M_PI_2],
            [180, M_PI],
            [45, M_PI_4],
            [270, 3 * M_PI_2],
            [0, 0],
            [-45, -M_PI_4],
            [-90, -M_PI_2],
            [-180, -M_PI],
            [-270, -3 * M_PI_2],
            [22.45, 0.391826417],
            [-215.45, -3.760311873],
        ];
    }

    /**
     * @param $startAngle
     * @param $turnDegrees
     * @param $expectedAngle
     * @dataProvider turnDataProvider
     */
    public function testTurn($startAngle, $turnDegrees, $expectedAngle)
    {
        $traveller = new Traveller();
        $traveller->setAngle($startAngle);
        $traveller->turn($turnDegrees);
        $this->assertEqualsWithDelta($expectedAngle, $traveller->getCurrentAngle(), 0.001);
    }

    public function turnDataProvider()
    {
        return [
            [0, 90, 90],
            [0, -90, -90],
            [45, 45, 90],
            [45, -45, 0],
            [45, -90, -45],
            [0, 359, 359],
            [10.45, 1.15, 11.60],
            [10.45, -1.15, 9.30],
            [10.45, -10.45, 0],
            [5, -10, -5],
        ];
    }

    /**
     * @param Point $startPoint
     * @param float $startAngle
     * @param float $moveDistance
     * @param Point $expectedPoint
     *
     * @dataProvider walkDataProvider
     */
    public function testWalk(Point $startPoint, float $startAngle, float $moveDistance, Point $expectedPoint)
    {
        $traveller = new Traveller();
        $traveller->setPoint($startPoint);
        $traveller->setAngle($startAngle);
        $traveller->walk($moveDistance);
        $this->assertEqualsWithDelta($expectedPoint->x, $traveller->getCurrentPoint()->x, 0.001);
        $this->assertEqualsWithDelta($expectedPoint->y, $traveller->getCurrentPoint()->y, 0.001);
    }

    public function walkDataProvider()
    {
        return [
            [new Point(0, 0), 0, 0, new Point(0, 0)],
            [new Point(0, 0), 0, 10, new Point(10, 0)],
            [new Point(0, 0), 0, -10, new Point(-10, 0)],
            [new Point(0, 0), 90, 10, new Point(0, 10)],
            [new Point(10, 10), 90, 10, new Point(10, 20)],
            [new Point(-10, -5), 90, 10, new Point(-10, 5)],
            [new Point(-10, -5), 40, 10, new Point(-2.340, 1.4279)],
            [new Point(10, -5), -180, 7, new Point(3, -5)],
        ];
    }
}
