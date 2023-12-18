<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');
const MOVES = ['L' => [0, -1], 'D' => [1, 0], 'R' => [0, 1], 'U' => [-1, 0]];
const HEX_TO_DIR = ['R','D','L','U'];

$instructions1 = [];
$instructions2 = [];
while ($line = trim(fgets($file))) {
    // Part 1
    $line = explode(' ', $line);
    $instructions1[] = ['dir' => $line[0], 'length' => $line[1]];

    // Part 2
    $hex = $line[2];
    $dir = HEX_TO_DIR[$hex[7]];
    $length = hexdec(substr($hex, 2, 5));

    // echo "[$dir, $length]\n";
    $instructions2[] = ['dir' => $dir, 'length' => $length];
}
// die();

echo "Nb points part 1: " . getVolume($instructions1) . "\n";
echo "Nb points part 2: " . getVolume($instructions2) . "\n";


function getVolume($instructions)
{
    $vertices = getVertices($instructions);
    $perimeter = getPerimeter($instructions);
    $area = getArea($vertices);
    $pointsInside = $area - $perimeter / 2 + 1;
    return $pointsInside + $perimeter;
}
function getVertices($instructions)
{
    $currentPos = [0, 0];
    $vertices = [$currentPos];

    foreach ($instructions as $instruction) {
        $nextPos = [
            $currentPos[0] + $instruction['length'] * MOVES[$instruction['dir']][0],
            $currentPos[1] + $instruction['length'] * MOVES[$instruction['dir']][1]
        ];
        $currentPos = $nextPos;
        $vertices[] = $currentPos;
    }
    return $vertices;
}

function getArea($vertices)
{
    $area = 0;
    for ($i = 0; $i < count($vertices) - 1; $i++) {
        $area += $vertices[$i][0] * $vertices[$i + 1][1]
            - $vertices[$i + 1][0] * $vertices[$i][1];
    }
    return abs($area / 2);
}

function getPerimeter($instructions)
{
    $perimeter = 0;
    foreach ($instructions as $i) {
        $perimeter += $i['length'];
    }
    return $perimeter;
}
