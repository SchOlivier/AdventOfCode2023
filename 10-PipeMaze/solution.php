<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$maze = [];
$vertices = [];
$i = 0;
while ($line = trim(fgets($file))) {
    $maze[] = $line;
    $pos = strpos($line, 'S');
    if ($pos !== false) {
        $start = ['row' => $i, 'col' => $pos];
    }
    $i++;
}

/**
 * | north and south.
 * - east and west.
 * L north and east.
 * J north and west.
 * 7 south and west.
 * F south and east.
 */
$length = 1;
$nextPipeFound = false;
// find a pipe connected to the start
// Check north
if ($start['row'] > 0 && in_array($maze[$start['row'] - 1][$start['col']], ['|', '7', 'F'])) {
    $from = 'S';
    $currentPosition = ['row' => $start['row'] - 1, 'col' => $start['col']];
    $nextPipeFound = true;
}
// Check South 
if ($start['row'] < count($maze) - 1 && in_array($maze[$start['row'] + 1][$start['col']], ['|', 'L', 'J'])) {
    if (!$nextPipeFound) {
        $from = 'N';
        $currentPosition = ['row' => $start['row'] + 1, 'col' => $start['col']];
        $nextPipeFound = true;
    }
    // not a vertice
}
// Check West 
if ($start['col'] > 0 && in_array($maze[$start['row']][$start['col'] - 1], ['-', 'L', 'F'])) {
    if (!$nextPipeFound) {
        $from = 'E';
        $currentPosition = ['row' => $start['row'], 'col' => $start['col'] - 1];
        $nextPipeFound = true;
    } else {
        $vertices[] = $start;
    }
}
// Check East 
if ($start['col'] < strlen($maze[0]) - 1 && in_array($maze[$start['row']][$start['col'] + 1], ['-', 'J', '7'])) {
    if ($from != 'E') {
        $vertices[] = $start;
    }
}
$currentPipe = $maze[$currentPosition['row']][$currentPosition['col']];

// follow the pipe until the end of the loop and count the steps, list the vertices in the process
while ($currentPipe != 'S') {
    $length++;
    switch ($currentPipe) {
        case  '|':
            if ($from == 'S') {
                $currentPosition = ['row' => $currentPosition['row'] - 1, 'col' => $currentPosition['col']];
            } else {
                $currentPosition = ['row' => $currentPosition['row'] + 1, 'col' => $currentPosition['col']];
            }
            break;
        case  '-':
            if ($from == 'E') {
                $currentPosition = ['row' => $currentPosition['row'], 'col' => $currentPosition['col'] - 1];
            } else {
                $currentPosition = ['row' => $currentPosition['row'], 'col' => $currentPosition['col'] + 1];
            }
            break;
        case  'L':
            $vertices[] = $currentPosition;
            if ($from == 'N') {
                $currentPosition = ['row' => $currentPosition['row'], 'col' => $currentPosition['col'] + 1];
                $from = 'W';
            } else {
                $currentPosition = ['row' => $currentPosition['row'] - 1, 'col' => $currentPosition['col']];
                $from = 'S';
            }
            break;
        case  'J':
            $vertices[] = $currentPosition;
            if ($from == 'N') {
                $currentPosition = ['row' => $currentPosition['row'], 'col' => $currentPosition['col'] - 1];
                $from = 'E';
            } else {
                $currentPosition = ['row' => $currentPosition['row'] - 1, 'col' => $currentPosition['col']];
                $from = 'S';
            }
            break;
        case  '7':
            $vertices[] = $currentPosition;
            if ($from == 'S') {
                $currentPosition = ['row' => $currentPosition['row'], 'col' => $currentPosition['col'] - 1];
                $from = 'E';
            } else {
                $currentPosition = ['row' => $currentPosition['row'] + 1, 'col' => $currentPosition['col']];
                $from = 'N';
            }
            break;
        case  'F':
            $vertices[] = $currentPosition;
            if ($from == 'S') {
                $currentPosition = ['row' => $currentPosition['row'], 'col' => $currentPosition['col'] + 1];
                $from = 'W';
            } else {
                $currentPosition = ['row' => $currentPosition['row'] + 1, 'col' => $currentPosition['col']];
                $from = 'N';
            }
            break;
    }
    $currentPipe = $maze[$currentPosition['row']][$currentPosition['col']];
    // if ($length > 5) break;
}
echo "loop length : $length\n";
echo "Max distance from start : " . $length / 2 . "\n";

// Part 2
/**
 * Pick's theorem:
 * For a polygon with integer coordinates for all its vertices
 * A = i + b/2 -1
 * 
 * With :
 * A : area of the polygon
 * i : number of points inside the polygon
 * b : number of points on the border
 */

// Loop area :
$area = 0;
$vertices[] = $vertices[0];
for ($i = 0; $i < count($vertices) - 1; $i++) {
    $area += $vertices[$i]['row'] * $vertices[$i + 1]['col']
        - $vertices[$i + 1]['row'] * $vertices[$i]['col'];
}
$area = abs($area / 2);

// Surface inside the loop
$insideArea = $area - $length / 2 + 1;

echo "Surface inside the loop : $insideArea\n";
