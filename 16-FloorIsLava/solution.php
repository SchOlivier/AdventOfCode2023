<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$map = [];
while ($line = trim(fgets($file))) {
    $map[] = str_split($line);
}
$height = count($map);
$width = count($map[0]);
$outOfBound = [];

$start = new BeamPosition(0, 0, 'R');
$count = pathBeam($start);
echo "Part 1 : $count\n";

// Part 2
$max = 0;
for ($col = 0; $col < $width; $col++) {
    $start = new BeamPosition(0, $col, 'D');
    $max = max($max, pathBeam($start));

    $start = new BeamPosition($height - 1, $col, 'U');
    $max = max($max, pathBeam($start));

}
for ($row = 0; $row < $height; $row++) {
    $start = new BeamPosition($row, 0, 'R');
    $max = max($max, pathBeam($start));


    $start = new BeamPosition($row, $height - 1, 'L');
    $max = max($max, pathBeam($start));

}

echo "Part 2 : $max\n";

function pathBeam(BeamPosition $start): int
{
    global $map;
    global $width;
    global $height;
    global $outOfBound;

    if (isset($outOfBound[$start->index()])) return 0;

    $queue = [];
    $seen = [];
    $energized = [];

    $seen[$start->index()] = $start;
    $queue[$start->index()] = $start;
    $energized[$start->row][$start->col] = true;
    $outOfBound[$start->index()] = $start;

    while (!empty($queue)) {
        $beam = dequeue($queue);

        $case = [$beam->direction, $map[$beam->row][$beam->col]];

        // going right
        if (in_array($case, [['R', '.'], ['R', '-'], ['U', '-'], ['U', '/'], ['D', '-'], ['D', '\\']])) {
            $right = new BeamPosition($beam->row, $beam->col + 1, 'R');
            if ($beam->col + 1 < $width && !isset($seen[$right->index()])) {
                $queue[$right->index()] = $right;
                $seen[$right->index()] = $right;
                $energized[$right->row][$right->col] = true;
            }
            if ($beam->col + 1 == $width) {
                $outOfBound[$beam->index()] = $beam;
            }
        }

        // going left
        if (in_array($case, [['L', '.'], ['L', '-'], ['U', '-'], ['U', '\\'], ['D', '-'], ['D', '/']])) {
            $left = new BeamPosition($beam->row, $beam->col - 1, 'L');
            if ($beam->col - 1 >= 0 && !isset($seen[$left->index()])) {
                $queue[$left->index()] = $left;
                $seen[$left->index()] = $left;
                $energized[$left->row][$left->col] = true;
            }
            if ($beam->col - 1 < 0) {
                $outOfBound[$beam->index()] = $beam;
            }
        }

        // going up
        if (in_array($case, [['U', '.'], ['U', '|'], ['R', '|'], ['R', '/'], ['L', '|'], ['L', '\\']])) {
            $up = new BeamPosition($beam->row - 1, $beam->col, 'U');
            if ($beam->row - 1 >= 0 && !isset($seen[$up->index()])) {
                $queue[$up->index()] = $up;
                $seen[$up->index()] = $up;
                $energized[$up->row][$up->col] = true;
            }
            if ($beam->row - 1 < 0) {
                $outOfBound[$beam->index()] = $beam;
            }
        }

        //going down
        if (in_array($case, [['D', '.'], ['D', '|'], ['R', '|'], ['R', '\\'], ['L', '|'], ['L', '/']])) {
            $down = new BeamPosition($beam->row + 1, $beam->col, 'D');
            if ($beam->row + 1 < $height && !isset($seen[$down->index()])) {
                $queue[$down->index()] = $down;
                $seen[$down->index()] = $down;
                $energized[$down->row][$down->col] = true;
            }
            if ($beam->row + 1 == $height) {
                $outOfBound[$beam->index()] = $beam;
            }
        }

        // display($energized, $map);
        // $q = readline("continue?");
        // if ($q == 'n') die();
    }

    $count = 0;
    foreach ($energized as $row) {
        $count += count($row);
    }
    return $count;
}

class BeamPosition
{
    public function __construct(public int $row, public int $col, public string $direction)
    {
    }

    public function index(): string
    {
        return $this->row . ',' . $this->col . ',' . $this->direction;
    }
}

function dequeue(&$queue): BeamPosition
{
    return array_shift($queue);
}

function display(array $energized, array $map)
{
    echo "\n\n";
    for ($row = 0; $row < 10; $row++) {
        for ($col = 0; $col < 10; $col++) {
            echo isset($energized[$row][$col]) ? '#' : $map[$row][$col];
        }
        echo "\n";
    }
}
