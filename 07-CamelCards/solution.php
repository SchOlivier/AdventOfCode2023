<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$hands = [];
$bids = [];
while ($line = trim(fgets($file))) {
    $handAndBid = explode(" ", $line);
    $hands[] = $handAndBid[0];
    $bids[$handAndBid[0]] = $handAndBid[1];
}

usort($hands, "compareHands");
$winnings = 0;
for ($i = 0; $i < count($hands); $i++) {
    $winnings += $bids[$hands[$i]] * ($i + 1);
}
echo "total winnings part 1: $winnings\n";

usort($hands, "compareHandsWithJokers");
$winnings = 0;
for ($i = 0; $i < count($hands); $i++) {
    $winnings += $bids[$hands[$i]] * ($i + 1);
}
echo "total winnings part 2: $winnings\n";


function compareHands($a, $b)
{
    if (getHandValue($a) != getHandValue($b)) return getHandValue($a) <=> getHandValue($b);

    $i = 0;
    while ($i < 5 && getCardValue($a[$i]) == getCardValue($b[$i])) {
        $i++;
    }

    if ($i == 5) return 0;
    return getCardValue($a[$i]) <=> getCardValue($b[$i]);
}

function compareHandsWithJokers($a, $b)
{
    if (getHandValueWithJokers($a) != getHandValueWithJokers($b)) return getHandValueWithJokers($a) <=> getHandValueWithJokers($b);

    $i = 0;
    while ($i < 5 && getCardValueWithJokers($a[$i]) == getCardValueWithJokers($b[$i])) {
        $i++;
    }

    if ($i == 5) return 0;
    return getCardValueWithJokers($a[$i]) <=> getCardValueWithJokers($b[$i]);
}

function getHandValue($hand)
{
    $count = [];
    for ($i = 0; $i < 5; $i++) {
        $count[$hand[$i]] = isset($count[$hand[$i]]) ? $count[$hand[$i]] + 1 : 1;
    }
    rsort($count);

    if ($count == [5]) return 6;
    if ($count == [4, 1]) return 5;
    if ($count == [3, 2]) return 4;
    if ($count == [3, 1, 1]) return 3;
    if ($count == [2, 2, 1]) return 2;
    if ($count == [2, 1, 1, 1]) return 1;
    return 0;
}

function getHandValueWithJokers($hand)
{
    $count = [];
    $jokers = 0;
    for ($i = 0; $i < 5; $i++) {
        if($hand[$i] == 'J'){
            $jokers++;
            continue;
        }
        $count[$hand[$i]] = isset($count[$hand[$i]]) ? $count[$hand[$i]] + 1 : 1;
    }
    if(empty($count)){
       return 6; 
    }
    rsort($count);
    $count[0] += $jokers;

    if ($count == [5]) return 6;
    if ($count == [4, 1]) return 5;
    if ($count == [3, 2]) return 4;
    if ($count == [3, 1, 1]) return 3;
    if ($count == [2, 2, 1]) return 2;
    if ($count == [2, 1, 1, 1]) return 1;
    return 0;
}

function getCardValue($card)
{
    $cardValue = [
        'A' => 14, 'K' => 13, 'Q' => 12,
        'J' => 11, 'T' => 10, '9' => 9,
        '8' => 8, '7' => 7, '6' => 6,
        '5' => 5, '4' => 4, '3' => 3, '2' => 2
    ];
    return $cardValue[$card];
}

function getCardValueWithJokers($card)
{
    $cardValue = [
        'A' => 14, 'K' => 13, 'Q' => 12,
        'J' => 1, 'T' => 10, '9' => 9,
        '8' => 8, '7' => 7, '6' => 6,
        '5' => 5, '4' => 4, '3' => 3, '2' => 2
    ];
    return $cardValue[$card];
}
