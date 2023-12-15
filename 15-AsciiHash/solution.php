<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$line = trim(fgets($file));
$steps = explode(',', $line);

$hashSum = 0;
foreach ($steps as $step) {
    $hashSum += getHash($step);
}
echo "Sum of hashes : $hashSum\n";

// Part 2:
$boxes = [];
foreach ($steps as $step) {
    if (substr($step, -1) == '-') {
        $label = substr($step, 0, strlen($step) - 1);
        $box = getHash($label);
        unset($boxes[$box][$label]);
    } else {
        $pos = strpos($step, '=');
        $label = substr($step, 0, $pos);
        $box = getHash($label);
        $size = substr($step, $pos + 1);
        $boxes[$box][$label] = $size;
    }
}

$focusingPowerSum = 0;
foreach ($boxes as $boxNumber => $lenses) {
    $slotNumber = 1;
    foreach ($lenses as $label => $size) {
        $focusingPowerSum += ($boxNumber + 1) * $slotNumber * $size;
        $slotNumber++;
    }
}

echo "Sum of focusing power : $focusingPowerSum\n";

function getHash(string $string): int
{
    $hash = 0;
    for ($i = 0; $i < strlen($string); $i++) {
        $hash += ord($string[$i]);
        $hash *= 17;
        $hash %= 256;
    }
    return $hash;
}
