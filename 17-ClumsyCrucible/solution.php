<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$grid = [];
while ($line = trim(fgets($file))) {
    $grid[] = str_split($line);
}

$height = count($grid);
$width = count($grid[0]);

const MOVES = ['L' => [0, -1], 'D' => [1, 0], 'R' => [0, 1], 'U' => [-1, 0]];
const OPPOSITE = ['L' => 'R', 'D' => 'U', 'R' => 'L', 'U' => 'D'];

function findShortestPath($start, $end, $maxDistance = 3, $minDistance = 0)
{
    global $grid, $height, $width;

    $seen = [];
    $heap = new SplMinHeap();

    $heap->insert(['heat' => 0, 'row' => $start[0], 'col' => $start[1], 'direction' => -1, 'directionCount' => 0]);
    $minHeat = INF;

    while (!$heap->isEmpty()) {
        $current = $heap->extract();

        $heat = $current['heat'];
        $row = $current['row'];
        $col = $current['col'];
        $direction = $current['direction'];
        $directionCount = $current['directionCount'];

        $key = "$row,$col,$direction,$directionCount";
        if (isset($seen[$key])) continue;
        $seen[$key] = $heat;

        if ([$row, $col] == $end) $minHeat = min($minHeat, $heat);

        foreach (MOVES as $nextDirection => $move) {
            $nextRow = $row + $move[0];
            $nextCol = $col + $move[1];
            if (
                $nextRow < 0 || $nextRow >= $height ||
                $nextCol < 0 || $nextCol >= $width ||
                OPPOSITE[$nextDirection] == $direction
            ) continue;

            $nextDirectionCount = ($nextDirection == $direction ? $directionCount + 1 : 1);
            if ($nextDirectionCount > $maxDistance) continue;
            if($heat > 0 && $nextDirection != $direction && $directionCount < $minDistance) continue;

            $nextHeat = $heat + $grid[$nextRow][$nextCol];
            $heap->insert([
                'heat' => $nextHeat,
                'row' => $nextRow,
                'col' => $nextCol,
                'direction' => $nextDirection,
                'directionCount' => $nextDirectionCount
            ]);
        }
    }
    return $minHeat;
}

$heat = findShortestPath([0, 0], [$height - 1, $width - 1]);
echo "part 1: $heat\n";


$heat = findShortestPath([0, 0], [$height - 1, $width - 1], 10, 4);
echo "part 2: $heat\n";

