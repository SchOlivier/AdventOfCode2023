<?php

// $path = 'exampleInput-1';
$path = 'puzzleInput';
$file = fopen($path, 'r');

// $blueprint=[];
$numbers = [];
$gears = [];
$row = 0;

while ($line = trim(fgets($file))) {
    // import each line of the input in an array
    $blueprint[] = $line;
    // create an array of [number, row, column]
    preg_match_all('/\d+/', $line, $numberMatches, PREG_OFFSET_CAPTURE);
    foreach ($numberMatches[0] as $number) {
        $numbers[] = ['value' => $number[0], 'row' => $row, 'col' => $number[1]];
    }

    $row++;
}

$width = strlen($blueprint[0]);
$height = count($blueprint);
$sum = 0;
$gears = [];

foreach ($numbers as $number) {
    $counted = false;

    $start = max(0, $number['col'] - 1);
    $end = min($width, $number['col'] + strlen($number['value']) + 1);
    $length = $end - $start;

    for ($row = max(0, $number['row'] - 1); $row <= min($height - 1, $number['row'] + 1); $row++) {
        $substr =  substr($blueprint[$row], $start, $length);
        //Part 1, just checking if there's a symbol around
        if (!$counted && preg_match('/[^\d\.]/', $substr)) {
            $sum += $number['value'];
            $counted = true;
        }

        //part 2, check if there's gears around
        preg_match_all('/\*/', $substr, $gearMatches, PREG_OFFSET_CAPTURE);
        foreach ($gearMatches[0] as $gear) {
            $index = $row . '_' . ($start + $gear[1]);
            $gears[$index][] = $number['value'];
        }
    }
}

echo "\nSum : $sum";

// Part 2 : for each gear, list the numbers around
$gearRatios = 0;
foreach ($gears as $gearNumbers) {
    if (count($gearNumbers) == 2) {
        $gearRatios += array_product($gearNumbers);
    }
}
echo "\nGear Ratio : $gearRatios";
