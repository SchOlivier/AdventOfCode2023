<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$total = 0;
$totalPart2 = 0;


while ($line = trim(fgets($file))) {
    $springs = str_split(explode(" ", $line)[0]);
    $groups = explode(',', explode(" ", $line)[1]);
    $nbArrangements  = countArrangements($springs, $groups, 0);
    // $a = readline("continue? ");
    // if($a == 'n') break;
    $total += $nbArrangements;

    $springsPart2 = array_merge(
        $springs,
        ['?'],
        $springs,
        ['?'],
        $springs,
        ['?'],
        $springs,
        ['?'],
        $springs
    );

    $groupsPart2 = array_merge($groups, $groups, $groups, $groups, $groups);

    $memo = [];
    $totalPart2 += countArrangements($springsPart2, $groupsPart2, 0);
}

echo "\n --- total possible arrangements : $total\n";
echo "\n --- total possible arrangements part 2 : $totalPart2\n";

function countArrangements(array $springs, array $remainingGroups, int $currentGroupLength): int
{
    global $memo;
    $index = implode('', $springs) . implode(',', $remainingGroups) . '-' . $currentGroupLength;
    if (isset($memo[$index])) return $memo[$index];

    if (empty($remainingGroups)) {
        // check if there is any '#' remaining
        if (in_array('#', $springs)) {
            return 0;
        }
        return 1;
    }

    if (empty($springs)) {
        if (count($remainingGroups) > 1) {
            return 0;
        }
        if ($remainingGroups[0] == $currentGroupLength) {
            return 1;
        }
        return 0;
    }

    $countBroken = 0;
    $countOperational = 0;
    $pos = array_shift($springs);

    if ($pos == '#' || $pos == '?') {


        // '?' considered as a broken spring '#'
        if ($currentGroupLength == 0) {
            // Start a group if none started
            $countBroken = countArrangements($springs, $remainingGroups, 1);
        } else {
            // else increase current group length and check it against group size
            if ($currentGroupLength + 1 <= $remainingGroups[0]) {
                // length valid, continuing
                $countBroken = countArrangements($springs, $remainingGroups, $currentGroupLength + 1);
            }
            // else length invalid, nothing to do
        }
    }

    if ($pos == '.' || $pos == '?') {
        // '?' considered as an operational spring '.'
        if ($currentGroupLength == 0) {
            // if no group started, just continue to the next position
            $countOperational = countArrangements($springs, $remainingGroups, 0);
        } elseif ($currentGroupLength == $remainingGroups[0]) {
            // current group is valid, remove it from remaining groups and get to next position with a group length of 0
            array_shift($remainingGroups);
            $countOperational = countArrangements($springs, $remainingGroups, 0);
        }
        // else current group is invalid, nothing to do
    }

    $memo[$index] = $countBroken + $countOperational;
    return $countBroken + $countOperational;
}
