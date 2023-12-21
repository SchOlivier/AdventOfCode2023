<?php

require 'AbstractModule.php';
require 'BroadcastModule.php';
require 'FlipFlopModule.php';
require 'ConjonctionModule.php';
require 'DummyModule.php';

// $path = 'exampleInput1';
// $path = 'exampleInput2';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$modules = [];
$listeners = [];
while ($line = trim(fgets($file))) {
    preg_match('/(broadcaster|[%&]\w+) -> (.*)/', $line, $matches);

    $name = $matches[1];
    $tos = explode(', ', $matches[2]);
    if ($name[0] == '%') {
        $module = new FlipFlopModule(substr($name, 1));
    } elseif ($name[0] == '&') {
        $module = new ConjonctionModule(substr($name, 1));
    } else {
        $module = new BroadcastModule('broadcaster');
        $broadCaster = $module;
    }
    $modules[$module->name] = $module;
    $listeners[$module->name] = $tos;
}

foreach ($listeners as $moduleName => $tos) {
    $publisher = $modules[$moduleName];
    foreach ($tos as $to) {
        if (!isset($modules[$to])) {
            $modules[$to] = new DummyModule($to);
        };
        $publisher->addListener($modules[$to]);
        $modules[$to]->addPublisher($publisher);
    }
}

resetModules();

// Part 1
$i = 0;
$cache = [];
$currentState = getState();
while (!isset($cache[$currentState]) && $i < 1000) {
    $i++;
    // echo "\n--- pushing button ! ---\n";
    $counts = pushButtonAndCountPulses($broadCaster);
    $cache[$currentState] = $counts;
    $currentState = getState();
}
$low = 0;
$high = 0;
foreach ($cache as $count) {
    $low += $count['low'];
    $high += $count['high'];
}
$score = $low * $high * (1000 / count($cache)) ** 2;
echo "total score : $score\n";

//Part 2

$gfCount = countUntilModuleEmitsPulse($broadCaster, 'gf', false);
echo "\nbutton pushes to get gf low pulse : $gfCount\n";
$vcCount = countUntilModuleEmitsPulse($broadCaster, 'vc', false);
echo "\nbutton pushes to get vc low pulse : $vcCount\n";
$dbCount = countUntilModuleEmitsPulse($broadCaster, 'db', false);
echo "\nbutton pushes to get db low pulse : $dbCount\n";
$qxCount = countUntilModuleEmitsPulse($broadCaster, 'qx', false);
echo "\nbutton pushes to get qx low pulse : $qxCount\n";

$lcm = lcm($gfCount, $vcCount);
$lcm = lcm($lcm, $dbCount);
$lcm = lcm($lcm, $qxCount);
echo "\nTotal button pushes : $lcm\n";

function pushButtonAndCountPulses(BroadcastModule $broadCaster): array
{
    $lowCount = 0;
    $highCount = 0;
    $rxLowPulseCount = 0;
    $queue = [['from' => null, 'to' => $broadCaster, 'pulse' => false]];
    while (!empty($queue)) {
        $item = array_shift($queue);
        // displayQueueItem($item);

        $from = $item['from'];
        $module = $item['to'];
        $pulse = $item['pulse'];

        if ($module->name == 'rx' && !$pulse) $rxLowPulseCount++;

        if ($pulse) {
            $highCount++;
        } else {
            $lowCount++;
        }

        $module->processPulse($pulse, $from, $queue);
    }
    return ['low' => $lowCount, 'high' => $highCount, 'rx' => $rxLowPulseCount];
}

function countUntilModuleEmitsPulse(BroadcastModule $broadCaster, string $moduleName, bool $searchedPulse): int
{
    resetModules();
    $buttonPushCount = 1;
    while (!pushButtonAndLookForPulse($broadCaster, $moduleName, $searchedPulse)) {
        $buttonPushCount++;
    }
    return $buttonPushCount;
}

function pushButtonAndLookForPulse(BroadcastModule $broadCaster, string $moduleName, bool $searchedPulse): bool
{
    $queue = [['from' => null, 'to' => $broadCaster, 'pulse' => false]];
    while (!empty($queue)) {
        $item = array_shift($queue);

        $from = $item['from'];
        $module = $item['to'];
        $pulse = $item['pulse'];

        if ($from && $from->name == $moduleName && $pulse == $searchedPulse) return true;
        $module->processPulse($pulse, $from, $queue);
    }
    return false;
}

function displayQueueItem($item)
{
    echo (is_null($item['from']) ? 'button' : $item['from']->name) . ' ';
    echo '-' . ($item['pulse'] ? 'high' : 'low');
    echo '-> ' . $item['to']->name . "\n";
}

function getState()
{
    global $modules;
    $state = [];
    foreach ($modules as $module) {
        $state[] = $module->getState();
    }
    return implode(',', $state);
}

function resetModules()
{
    global $modules;
    foreach ($modules as $module) {
        $module->reset();
    }
}

function lcm($a, $b)
{
    return $a * $b / gcd($a, $b);
}

function gcd($a, $b)
{
    return $b ? gcd($b, $a % $b) : $a;
}
