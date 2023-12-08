<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$directions = trim(fgets($file));
fgets($file);

$map = [];
while ($line = trim(fgets($file))) {
    //AAA = (BBB, CCC)
    preg_match('/(\w+) = \((\w+), (\w+)/', $line, $matches);
    $map[$matches[1]] = ['L' => $matches[2], 'R' => $matches[3]];
}

$position = 'AAA';
$i = 0;
$steps = 1;
while (($newPosition = $map[$position][$directions[$i]]) != 'ZZZ') {
    $position = $newPosition;
    $i++;
    if ($i == strlen($directions)) $i = 0;
    $steps++;
}
echo "it took $steps steps to get to the destination\n";

// part 2
$positions = [];
foreach ($map as $start => $end) {
    if ($start[2] == 'A') $positions[] = $start;
}

$steps = [];
foreach ($positions as $position) {
    $steps[$position] = countStepsToDestination($map, $directions, $position);
}

print_r($steps);
$lcm = 1;
foreach($steps as $step){
    $lcm = lcm($lcm, $step);
}
echo "Part 2 : it took $lcm steps to get to the destination\n";

function countStepsToDestination($map, $directions, $position)
{
    $i = 0;
    $steps = 0;
    while ($position[2] != 'Z') {
        $position = $map[$position][$directions[$i]];
        $i++;
        if ($i == strlen($directions)) $i = 0;
        $steps++;
    }
    return $steps;
}

function lcm($a, $b)
{
    return $a * $b / gcd($a, $b);
}

function gcd($a, $b)
{
    return $b ? gcd($b, $a % $b) : $a;
}
