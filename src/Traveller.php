<?php

namespace Rnix\Traveller;

class Traveller
{
    private const COMMAND_WALK = 'walk';
    private const COMMAND_TURN = 'turn';

    /** @var float */
    private $angle;

    /** @var Point */
    private $point;

    /**
     * @param array $trips array of full string of a trip
     * @return array [average x, average y, worst distance]
     */
    public static function analyzeTrips(array $trips): ?array
    {
        // 2.6762 75.2811 start -45.0 walk 40 turn 40.0 walk 60
        $destinations = [];
        foreach ($trips as $trip) {
            $tripData = explode(' ', $trip);
            list ($x, $y, $_, $angle) = $tripData;
            $traveller = new Traveller();
            $traveller->setPoint(new Point($x, $y));
            $traveller->setAngle($angle);
            $commandPairs = [];
            for ($i = 4; $i < count($tripData); $i = $i + 2) {
                $commandPairs[] = $tripData[$i] . ' ' . $tripData[$i + 1];
            }
            $destinations[] = $traveller->getFinalDestination($commandPairs);
        }

        if (!count($destinations)) {
            return null;
        }

        $averagePoint = self::getAveragePoint($destinations);
        $worstDistance = self::getWorstDistanceToPoint($destinations, $averagePoint);
        return [$averagePoint->x, $averagePoint->y, $worstDistance];
    }

    public function getFinalDestination(array $commandPairs): Point
    {
        foreach ($commandPairs as $pair) {
            list ($command, $argument) = explode(' ', $pair);
            switch ($command) {
                case self::COMMAND_WALK:
                    $this->walk((float) $argument);
                    break;
                case self::COMMAND_TURN;
                    $this->turn((float) $argument);
                    break;
            }
        }

        return $this->getCurrentPoint();
    }

    public function turn(float $degrees): void
    {
        $this->angle += $degrees;
    }

    public function walk(float $distance): void
    {
        $this->point->x += cos(self::getRadiansFromDegrees($this->angle)) * $distance;
        $this->point->y += sin(self::getRadiansFromDegrees($this->angle)) * $distance;
    }

    public function setAngle(float $degrees): void
    {
        $this->angle = $degrees;
    }

    public function setPoint(Point $point): void
    {
        $this->point = $point;
    }

    public function getCurrentAngle(): float
    {
        return $this->angle;
    }

    public function getCurrentPoint(): Point
    {
        return $this->point;
    }

    public static function getRadiansFromDegrees(float $degrees): float
    {
        return $degrees * M_PI / 180.0;
    }

    private static function getAveragePoint(array $points): ?Point
    {
        if (!count($points)) {
            return null;
        }

        $averageX = array_reduce($points, function (float $carry, Point $point) {
                return $carry + $point->x;
            }, 0.0) / count($points);

        $averageY = array_reduce($points, function (float $carry, Point $point) {
                return $carry + $point->y;
            }, 0.0) / count($points);

        return new Point($averageX, $averageY);
    }

    /**
     * @param Point[] $points
     * @param Point $targetPoint
     * @return float|null
     */
    private static function getWorstDistanceToPoint(array $points, Point $targetPoint): ?float
    {
        if (!count($points)) {
            return null;
        }

        $distances = [];
        foreach ($points as $point) {
            $distances[] = $point->distanceTo($targetPoint);
        }

        return max($distances);
    }
}
