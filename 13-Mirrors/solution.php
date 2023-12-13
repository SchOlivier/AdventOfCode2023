<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$pattern = [];
$solution1 = 0;
$solution2 = 0;
while ($line = fgets($file)) {
    if (trim($line) == '') {
        $solution1 += solvePattern($pattern);
        $solution2 += solvePattern($pattern, true);
        $pattern = [];
    } else {
        $pattern[] = str_split(trim($line));
    }
}

echo "solution part 1: $solution1\n";
echo "solution part 2: $solution2\n";

function solvePattern(array $pattern, $withSmudge = false)
{
    for ($i = 0; $i < count($pattern) - 1; $i++) {
        if (
            !$withSmudge && checkSymetry($pattern, $i) ||
            $withSmudge && checkSymetryWithSmudge($pattern, $i)
        ) return 100 * ($i + 1);
    }

    //Transposition
    $pattern = array_map(null, ...$pattern);

    for ($i = 0; $i < count($pattern) - 1; $i++) {
        if (
            !$withSmudge && checkSymetry($pattern, $i) ||
            $withSmudge && checkSymetryWithSmudge($pattern, $i)
        ) return $i + 1;
    }

    return 0;
}

function checkSymetry($pattern, $startIndex)
{
    $i = $startIndex;
    $j = $i + 1;
    while ($i >= 0 && $j < count($pattern)) {
        if ($pattern[$i] != $pattern[$j]) return false;
        $i--;
        $j++;
    }

    return true;
}

function checkSymetryWithSmudge($pattern, $startIndex)
{
    $smudgeCount = 0;
    $i = $startIndex;
    $j = $i + 1;
    while ($i >= 0 && $j < count($pattern)) {
        $lineI = $pattern[$i];
        $lineJ = $pattern[$j];
        $i--;
        $j++;
        if ($lineI == $lineJ) {
            continue;
        } else {
            if (!haveOneDifference($lineI, $lineJ)) return false;
            $smudgeCount++;
            if ($smudgeCount > 1) return false;
        }
    }

    return $smudgeCount == 1;
}

function haveOneDifference(array $lineI, array $lineJ)
{
    $count = 0;
    for ($i = 0; $i < count($lineI); $i++) {
        if ($lineI[$i] != $lineJ[$i]) {
            $count++;
            if ($count > 1) return false;
        }
    }
    return $count == 1;
}
