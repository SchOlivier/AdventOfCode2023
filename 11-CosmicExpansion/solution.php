<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

// $expansionFactor = 2; // Part 1
$expansionFactor = 1e6; // Part 2

$map = [];
$emptyColumns = [];
$emptyRows = [];

$i = 0;
// Creating the map and listing the empty columns
while ($line = trim(fgets($file))) {
    $map[] = str_split($line);
    if (strpos($line, '#') === false) {
        $emptyColumns[] = $i;
    }
    $i++;
}

// Transposition
$map = array_map(null, ...$map);

// // Displaying map
// foreach($map as $line){
//     echo implode('', $line) . "\n";
// }

// finding empty lines and listing galaxies
$galaxies = [];
// echo "Galaxies :\n";
foreach ($map as $row => $line) {
    $noGalaxyInLine = true;
    foreach ($line as $col => $char) {
        if ($char == '#') {
            $galaxies[] = [$row, $col];
            // echo "[$row, $col]\n";
            $noGalaxyInLine = false;
        }
    }
    if ($noGalaxyInLine) $emptyRows[] = $row;
}

// echo "Empty rows : " . implode(', ', $emptyRows) . "\n";
// echo "Empty columns : " . implode(', ', $emptyColumns) . "\n";

$distancesSum = 0;

for ($i = 0; $i < count($galaxies); $i++) {
    for ($j = $i + 1; $j < count($galaxies); $j++) {
        $distancesSum += getDistanceWithExpansion($galaxies[$i], $galaxies[$j], $expansionFactor, $emptyRows, $emptyColumns);
    }
}
echo "Total distance : $distancesSum\n";

function getDistanceWithExpansion($galaxy1, $galaxy2, $expansionFactor, $emptyRows, $emptyColumns): int
{
    $verticalDistance =  abs($galaxy2[0] - $galaxy1[0]);
    $horizontalDistance = abs($galaxy2[1] - $galaxy1[1]);

    foreach ($emptyRows as $row) {
        if (
            $galaxy1[0] < $row && $row < $galaxy2[0] ||
            $galaxy2[0] < $row && $row < $galaxy1[0]
        ) {
            $verticalDistance += $expansionFactor - 1;
        }
    }

    foreach ($emptyColumns as $col) {
        if (
            $galaxy1[1] < $col && $col < $galaxy2[1] ||
            $galaxy2[1] < $col && $col < $galaxy1[1]
        ) {
            $horizontalDistance += $expansionFactor - 1;
        }
    }

    return $verticalDistance + $horizontalDistance;
}
