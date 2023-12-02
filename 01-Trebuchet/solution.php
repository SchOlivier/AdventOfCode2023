<?php
$path = 'exampleInput-1';
// $path = 'exampleInput-2';
// $path = 'puzzleInput';
$file = fopen($path, 'r');

$sumPartOne = 0;
$sumPartTwo = 0;
$valuesPartTwo = getPartTwoValues();
$patternPartTwo = "/(?=(\d|one|two|three|four|five|six|seven|eight|nine))/"; // use of lookahead because we have overlapping matches

while ($line = trim(fgets($file))) {
    $sumPartOne += getFirstAndLastDigits($line);
    $sumPartTwo += getFirstAndLastSpelledDigits($line);
}
echo "Total part 1: " . $sumPartOne . "\n";
echo "Total part 2: " . $sumPartTwo . "\n";

function getFirstAndLastDigits($line): int
{
    preg_match_all("/\d/", $line, $matches);
    return (int) ($matches[0][0] . end($matches[0]));
}

function getFirstAndLastSpelledDigits($line): int
{
    global $patternPartTwo;
    global $valuesPartTwo;
    preg_match_all($patternPartTwo, $line, $matches);

    $first = $valuesPartTwo[$matches[1][0]];
    $last = $valuesPartTwo[end($matches[1])];
    return 10*$first + $last;
}

function getPartTwoValues(): array
{
    return [
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5,
        'six' => 6,
        'seven' => 7,
        'eight' => 8,
        'nine' => 9
    ];
}
