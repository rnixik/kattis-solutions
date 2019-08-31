<?php

namespace Rnix\Traveller\Tests;

use PHPUnit\Framework\TestCase;
use Rnix\Traveller\Point;
use Rnix\Traveller\Traveller;

class TravellerTest extends TestCase
{
    /**
     * @param array $trips
     * @param float $expectedX
     * @param float $expectedY
     * @param float $expectedWorstDistance
     *
     * @dataProvider analyzeTripsDataProvider
     */
    public function testAnalyzeTrips(array $trips, float $expectedX, float $expectedY, float $expectedWorstDistance)
    {
        list ($actualX, $actualY, $actualWorstDistance) = Traveller::analyzeTrips($trips);
        $this->assertEqualsWithDelta($expectedX, $actualX, 0.001);
        $this->assertEqualsWithDelta($expectedY, $actualY, 0.001);
        $this->assertEqualsWithDelta($expectedWorstDistance, $actualWorstDistance, 0.001);
    }

    public function analyzeTripsDataProvider()
    {
        return [
            [
                [
                    '87.342 34.30 start 0 walk 10.0',
                    '2.6762 75.2811 start -45.0 walk 40 turn 40.0 walk 60',
                    '58.518 93.508 start 270 walk 50 turn 90 walk 40 turn 13 walk 5',
                ],
                97.1547,
                40.2334,
                7.63097,
            ],
            [
                [
                    '30 40 start 90 walk 5',
                    '40 50 start 180 walk 10 turn 90 walk 5',
                ],
                30,
                45,
                0,
            ],
        ];
    }

    /**
     * @param Point $startPoint
     * @param float $startAngle
     * @param array $commands
     * @param Point $expected
     *
     * @dataProvider getFinalDestinationDataProvider
     */
    public function testGetFinalDestination(Point $startPoint, float $startAngle, array $commands, Point $expected)
    {
        $traveller = new Traveller();
        $traveller->setPoint($startPoint);
        $traveller->setAngle($startAngle);
        $actualPoint = $traveller->getFinalDestination($commands);
        $this->assertEqualsWithDelta($expected->x, $actualPoint->x, 0.001);
        $this->assertEqualsWithDelta($expected->y, $actualPoint->y, 0.001);
    }

    public function getFinalDestinationDataProvider()
    {
        return [
            // 87.342 34.30 start 0 walk 10.0
            [
                new Point(87.342, 34.30),
                0,
                ['walk 10.0'],
                new Point(97.342, 34.30),
            ],
            // 2.6762 75.2811 start -45.0 walk 40 turn 40.0 walk 60
            [
                new Point(2.6762, 75.2811),
                -45.0,
                ['walk 40', 'turn 40.0', 'walk 60'], // (30.9605, 46,9968);
                new Point(90.7322, 41.7675),
            ],
            // 58.518 93.508 start 270 walk 50 turn 90 walk 40 turn 13 walk 5
            [
                new Point(58.518, 93.508),
                270,
                ['walk 50', 'turn 90', 'walk 40', 'turn 13', 'walk 5'], // (58.518, 43.508); (98.518, 43.508);
                new Point(103.3899, 44.6328),
            ],
            // 30 40 start 90 walk 5
            [
                new Point(30, 40),
                90,
                ['walk 5'],
                new Point(30, 45),
            ],
            // 40 50 start 180 walk 10 turn 90 walk 5
            [
                new Point(40, 50),
                180,
                ['walk 10', 'turn 90', 'walk 5'], // (30, 50);
                new Point(30, 45),
            ],
        ];
    }

    /**
     * @param float $degrees
     * @param float $expected
     *
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
     *
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
