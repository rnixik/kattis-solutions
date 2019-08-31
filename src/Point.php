<?php

namespace Rnix\Traveller;

class Point
{
    /** @var float */
    public $x;

    /** @var float */
    public $y;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function distanceTo(Point $point): float
    {
        return sqrt(pow($point->x - $this->x, 2) + pow($point->y - $this->y, 2));
    }
}
