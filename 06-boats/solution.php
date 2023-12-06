<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$times = trim(fgets($file));
$distances = trim(fgets($file));

$times = explode(' ', trim(preg_replace('/\s+/', ' ', substr($times, 5))));
$distances = explode(' ', trim(preg_replace('/\s+/', ' ', substr($distances, 9))));

$records = [];
foreach ($times as $i => $time) {
    $records[] = [
        'time' => (int)$time,
        'distance' => (int)$distances[$i]
    ];
}

/**
 * d: distance parcourue
 * t: temps d'appui du bouton
 * T: Temps total disponible
 * D: record de distance
 * 
 * d = t(T-t)
 * 
 * On veut d > D, soit :
 * t(T-t)>D, soit :
 * t^2 - tT + D < 0
 * 
 * avec Δ = T^2 -4D
 * On veut :
 * (T - √Δ)/2 < t < (T + √Δ)/2
 */
$product = 1;
foreach ($records as $i => $record) {
    echo "Record $i, time: " . $record['time'] . ", distance: " . $record['distance'] . "\n";

    $nbSolutions = getNumberOfSolutionsForRecord($record);

    echo "$nbSolutions solutions\n\n";
    $product *= $nbSolutions;
}
echo "Part 1 - Total product of solutions : $product\n";

// Part 2
rewind($file);
$time = preg_replace('/\D/', '', trim(fgets($file)));
$distance = preg_replace('/\D/', '', trim(fgets($file)));
$nbSolutions = getNumberOfSolutionsForRecord(['time' => $time, 'distance' => $distance]);
echo "Part 2 - $nbSolutions possible solutions\n";

function getNumberOfSolutionsForRecord(array $record): int
{
    $delta = $record['time'] ** 2 - 4 * $record['distance'];
    $tMin = ($record['time'] - sqrt($delta)) / 2;
    $tMax = ($record['time'] + sqrt($delta)) / 2;

    return ceil($tMax - 1) - floor($tMin + 1) + 1;
}
