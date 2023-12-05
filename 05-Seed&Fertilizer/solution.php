<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

// seeds
$line = trim(fgets($file));
$seeds = explode(' ', substr($line, 7));
fgets($file);

$seedRanges = [];
for ($i = 0; $i < count($seeds); $i += 2) {
    $seedRanges[] = [
        'start' => $seeds[$i],
        'end' => $seeds[$i] + $seeds[$i + 1] - 1
    ];
}

// maps
$maps = [];
while ($map = createMap($file)) {
    $maps[] = $map;
}

// Part 1
$seedToLocation = [];
foreach ($seeds as $seed) {
    $seedToLocation[$seed] = getLocationFromSeed($seed, $maps);
}
$min = min($seedToLocation);
echo "Part 1 minimum location : $min\n";

// Part 2
$currentRanges = $seedRanges;
foreach ($maps as $i => $map) {
    $newRanges = [];
    foreach ($currentRanges as $range) {
        $newRanges = array_merge($newRanges, mapRangeWithMap($range, $map));
    }
    $currentRanges = $newRanges;
}

$min = PHP_INT_MAX;
foreach ($currentRanges as $range) {
    $min = min($min, $range['start']);
}
echo "Part 2 minimum location : $min\n";

// end of main()

function getLocationFromSeed(int $seed, array $maps)
{
    $source = $seed;
    foreach ($maps as $map) {
        foreach ($map as $range) {
            if ($range['sourceStart'] > $source) continue 2;
            if (
                $range['sourceStart'] <= $source &&
                $source <= $range['sourceEnd']
            ) {
                $source = $range['destinationStart'] + $source - $range['sourceStart'];
                continue 2;
            }
        }
    }
    return $source;
}

function mapRangeWithMap(array $range, array $map): array
{
    $currentRange = $range;
    $mappedRanges = [];
    foreach ($map as $mapRange) {
        if ($currentRange['end'] < $mapRange['sourceStart']) {
            $mappedRanges[] = $currentRange;
            break;
        }
        if ($currentRange['start'] > $mapRange['sourceEnd']) {
            continue;
        }

        // left part of current range not covered by mapRange
        if ($currentRange['start'] < $mapRange['sourceStart']) {
            $mappedRanges[] = [
                'start' => $currentRange['start'],
                'end' => $mapRange['sourceStart'] - 1
            ];
            $currentRange['start'] = $mapRange['sourceStart'];
        }

        // intersection between current range and mapRange
        $end = min($mapRange['sourceEnd'], $currentRange['end']);
        $mappedRanges[] = [
            'start' => $mapRange['destinationStart'] + $currentRange['start'] - $mapRange['sourceStart'],
            'end' => $mapRange['destinationStart'] + $end - $mapRange['sourceStart']
        ];
        $currentRange['start'] = $end + 1;

        if ($currentRange['start'] > $currentRange['end']) break;
    }

    if ($currentRange['start'] <= $currentRange['end']) {
        $mappedRanges[] = $currentRange;
    }
    return $mappedRanges;
}

function createMap($file)
{
    $map = [
        // [seedStart, seedEnd, soilStart]
    ];

    $line = fgets($file);
    if (!$line) return false;

    while (($line = trim(fgets($file))) != '') {
        $line = explode(' ', $line);
        $map[] = [
            'sourceStart' => $line[1],
            'sourceEnd' => $line[1] + $line[2] - 1,
            'destinationStart' => $line[0]
        ];
        sortArray($map);
    }
    return $map;
}

function sortArray(&$array)
{
    usort($array, function ($a, $b) {
        return $a['sourceStart'] <=> $b['sourceStart'];
    });
}
