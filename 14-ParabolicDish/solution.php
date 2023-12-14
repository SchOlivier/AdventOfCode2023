<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$map = [];

while ($line = trim(fgets($file))) {
    $map[] = str_split($line);
}

$height = count($map);
$width = count($map[0]);

// Part 1
$mapPart1 = moveNorth($map, $height, $width);
$weight = getWeight($mapPart1, $height, $width);
echo "Weight part 1 : $weight\n\n";

// Part 2
$cache = [];
$cacheSize = 0;
$nbCycles = 1e9;
for ($i = 0; $i < $nbCycles; $i++) {
    $index = serialize($map);
    if (isset($cache[$index])) break;
    $map = doCycle($map, $height, $width);
    $cache[$index] = ['map' => $map, 'step' => $i];
}
echo "Started looping after " . $i . " cycles\n";
$loopStart = $cache[$index]['step'];
echo "Going back to step $loopStart\n";
$loopSize = $i - $loopStart;

$finalIndex = $loopStart + ($nbCycles - $loopStart) % $loopSize -1;
echo "Final index (maybe) : $finalIndex\n";

foreach ($cache as $value) {
    if ($value['step'] == $finalIndex) {
        $weight = getWeight($value['map'], $height, $width);
        break;
    }
}
echo "Weight part 2 (maybe) : $weight\n";


// $weight = getWeight($map, $height, $width);
// echo "\nWeight part 2 : $weight\n";

// $nextCycleMap = doCycle($map, $height, $width);
// echo "\n---------------\nAfter cycle:\n";
// displayMap($nextCycleMap, $height, $width);
// while ($nextCycleMap != $map) {
//     $map = $nextCycleMap;
//     $nextCycleMap = doCycle($map, $height, $width);
//     echo "\n---------------\nAfter cycle:\n";
//     displayMap($nextCycleMap, $height, $width);
//     $a = readline("continue?");
//     if ($a == 'n') die();
// }
// $weight = getWeight($mapPart1, $height, $width);
// echo "Weight part 2 : $weight\n";


function doCycle(array $map, int $height, int $width): array
{
    // echo "\n---------------\nStarting cycle with:\n";
    // displayMap($map, $height, $width);

    $map = moveNorth($map, $height, $width);
    // echo "\n---North:\n";
    // displayMap($map, $height, $width);

    $map = moveWest($map, $height, $width);
    // echo "\n---West:\n";
    // displayMap($map, $height, $width);

    $map = moveSouth($map, $height, $width);
    // echo "\n---South:\n";
    // displayMap($map, $height, $width);

    $map = moveEast($map, $height, $width);
    // echo "\n---East:\n";
    // displayMap($map, $height, $width);

    return $map;
}

function moveNorth(array $map, int $height, int $width): array
{
    $newMap = [];
    for ($col = 0; $col < $width; $col++) {
        $pos = 0;
        for ($row = 0; $row < $height; $row++) {
            if (!isset($map[$row][$col])) continue;
            if ($map[$row][$col] == 'O') {
                $newMap[$pos][$col] = 'O';
                $pos++;
            } elseif ($map[$row][$col] == '#') {
                $newMap[$row][$col] = '#';
                $pos = $row + 1;
            }
        }
    }
    return $newMap;
}

function moveWest(array $map, int $height, int $width): array
{
    $newMap = [];
    for ($row = 0; $row < $height; $row++) {
        $pos = 0;
        for ($col = 0; $col < $width; $col++) {
            if (!isset($map[$row][$col])) continue;
            if ($map[$row][$col] == 'O') {
                $newMap[$row][$pos] = 'O';
                $pos++;
            } elseif ($map[$row][$col] == '#') {
                $newMap[$row][$col] = '#';
                $pos = $col + 1;
            }
        }
    }
    return $newMap;
}

function moveSouth(array $map, int $height, int $width): array
{
    $newMap = [];
    for ($col = 0; $col < $width; $col++) {
        $pos = $height - 1;
        for ($row = $height - 1; $row >= 0; $row--) {
            if (!isset($map[$row][$col])) continue;
            if ($map[$row][$col] == 'O') {
                $newMap[$pos][$col] = 'O';
                $pos--;
            } elseif ($map[$row][$col] == '#') {
                $newMap[$row][$col] = '#';
                $pos = $row - 1;
            }
        }
    }
    return $newMap;
}

function moveEast(array $map, int $height, int $width): array
{
    $newMap = [];
    for ($row = 0; $row < $height; $row++) {
        $pos = $width - 1;
        for ($col = $width - 1; $col >= 0; $col--) {
            if (!isset($map[$row][$col])) continue;
            if ($map[$row][$col] == 'O') {
                $newMap[$row][$pos] = 'O';
                $pos--;
            } elseif ($map[$row][$col] == '#') {
                $newMap[$row][$col] = '#';
                $pos = $col - 1;
            }
        }
    }
    return $newMap;
}

function getWeight(array $map, int $height, int $width): int
{
    $weight = 0;
    for ($col = 0; $col < $width; $col++) {
        for ($row = 0; $row < $height; $row++) {
            if (isset($map[$row][$col]) && $map[$row][$col] == 'O') {
                $weight += $height - $row;
            }
        }
    }
    return $weight;
}

function displayMap(array $map, int $height, int $width)
{
    for ($row = 0; $row < $height; $row++) {
        for ($col = 0; $col < $width; $col++) {
            echo $map[$row][$col] ?? '.';
        }
        echo "\n";
    }
}
