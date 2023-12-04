<?php

// $path = 'exampleInput-1';
// $path = 'exampleInput-2';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$points = 0;
$cards = [];

while ($line = trim(fgets($file))) {
    $line = str_replace('  ', ' ', $line);
    preg_match('/Card\s+(\d+): (.*) \| (.*)/', $line, $card);
    $cardNumber = $card[1];
    $winning = explode(' ', $card[2]);
    $numbers = explode(' ', $card[3]);

    $nbWinning = count(array_intersect($winning, $numbers));

    // part 1
    $points += $nbWinning ? 2**($nbWinning - 1) : 0;

    // part 2
    $cards[$cardNumber] = 1 + ($cards[$cardNumber] ?? 0);
    for($nbWinning; $nbWinning > 0; $nbWinning--){
        $cards[$cardNumber + $nbWinning] = $cards[$cardNumber] + ($cards[$cardNumber + $nbWinning] ?? 0);
    }
}
echo "Total points : $points\n";
echo "Total cards : " . array_sum($cards) . "\n";