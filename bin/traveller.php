#!/usr/bin/php
<?php

use Rnix\Traveller\Traveller;

require __DIR__ . '/../vendor/autoload.php';

$tripsBuffer = [];
while (($line = fgets(STDIN)) !== false) {
    $line = trim($line);
    if (is_numeric($line)) {
        if ($tripsBuffer) {
            $analyzeResult = Traveller::analyzeTrips($tripsBuffer);
            if ($analyzeResult) {
                list($x, $y, $d) = $analyzeResult;
                fprintf(STDOUT, "%.6g %.6g %.6g\n", $x, $y, $d);
            }
            $tripsBuffer = [];
        }
    } else {
        $tripsBuffer[] = $line;
    }
    if ($line === '0') {
        break;
    }
}
