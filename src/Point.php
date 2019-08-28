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
}
