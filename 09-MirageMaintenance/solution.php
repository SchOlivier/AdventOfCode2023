<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$sumNext = 0;
$sumPrevious = 0;
while ($line = trim(fgets($file))) {
    $data = explode(' ', $line);
    $sumNext += getNextValue($data);
    $sumPrevious += getPreviousValue($data);
}

echo "Sum of next values : $sumNext\n";
echo "Sum of previous values : $sumPrevious\n";

function getNextValue($data){
    if(array_unique($data) == [0]) return 0;
    $derivated = [];
    for($i = 0; $i < count($data) - 1; $i++){
        $derivated[] = $data[$i+1] - $data[$i];
    }
    return end($data) + getNextValue($derivated);
}

function getPreviousValue($data){
    if(array_unique($data) == [0]) return 0;
    $derivated = [];
    for($i = 0; $i < count($data) - 1; $i++){
        $derivated[] = $data[$i+1] - $data[$i];
    }
    return $data[0] - getPreviousValue($derivated);
}
