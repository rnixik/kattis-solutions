<?php

namespace Rnix\Traveller;

class Traveller
{
    /** @var float */
    private $angle;

    /** @var Point */
    private $point;

    /*
    public function getFinalDestination(Point $start, array $commands): Point
    {
        return new Point(1.10, 3.20);
    }
    */

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
}
