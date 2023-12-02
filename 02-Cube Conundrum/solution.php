<?php
// $path = 'exampleInput-1';
$path = 'puzzleInput';
$file = fopen($path, 'r');

/*********
* PART 1 *
*********/

$maxCubes = [
    'red' => 12,
    'green' => 13,
    'blue' => 14,
];

$sumOfIds = 0;

while ($game = trim(fgets($file))) {

    preg_match('/Game (\d+):/', $game, $id);
    $id = $id[1];
    // echo "ID : " . $id . "\n";

    preg_match_all('/(\d+) (red|green|blue)/', $game, $cubes);
    // print_r($cubes);
    for ($i = 0; $i < count($cubes[0]); $i++) {
        if ($cubes[1][$i] > $maxCubes[$cubes[2][$i]]) {
            // echo $cubes[0][$i] . " : not possible\n";
            continue 2;
        }
    }

    // echo "Game $id is valid\n";
    $sumOfIds += $id;
}

echo "\nSum of valid game ids : $sumOfIds\n";

/*********
* PART 2 *
*********/

rewind($file);

$sumOfPowers = 0;

while ($game = trim(fgets($file))) {

    $minCubes = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];

    preg_match('/Game (\d+):/', $game, $id);

    preg_match_all('/(\d+) (red|green|blue)/', $game, $cubes);
    // print_r($cubes);
    for ($i = 0; $i < count($cubes[0]); $i++) {
        if ($cubes[1][$i] > $minCubes[$cubes[2][$i]]) {
            $minCubes[$cubes[2][$i]] = $cubes[1][$i];
        }
    }

    $power = array_product($minCubes);
    // echo $id[0] . " $power power\n";

    $sumOfPowers += $power;
}

echo "\nSum of powers : $sumOfPowers";
