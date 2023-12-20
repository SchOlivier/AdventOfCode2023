<?php

// $path = 'exampleInput';
$path = 'puzzleInput';
$file = fopen($path, 'r');

$ruleSets = [];
while ($line = trim(fgets($file))) {
    $name = substr($line, 0, strpos($line, '{'));
    preg_match('/{(.*)}/', $line, $stringRules);

    $rules = explode(',', $stringRules[1]);
    foreach ($rules as $rule) {
        $ruleSets[$name][] =  new RuleParam($rule);
    }
}
$items = [];
while ($line = trim(fgets($file))) {
    preg_match_all('/{x=(\d+),m=(\d+),a=(\d+),s=(\d+)}/', $line, $params);
    $items[] = ['x' => $params[1][0], 'm' => $params[2][0], 'a' => $params[3][0], 's' => $params[4][0]];
}

// Part 1
$result = 0;
foreach ($items as $item) {
    $out = applyRules('in', $item);

    while (!in_array($out, ['A', 'R'])) {
        $out = applyRules($out, $item);
    }

    if ($out == "A") {
        $result += array_sum($item);
    }
}

echo "Result : $result\n";

// part 2
// find rule paths that lead to A

$validConstraints = [];
$paths = [];
$stack = [['in', [
    'minx' => 1, 'maxx' => 4000,
    'minm' => 1, 'maxm' => 4000,
    'mina' => 1, 'maxa' => 4000,
    'mins' => 1, 'maxs' => 4000
], '']];

while (!empty($stack)) {
    $node = array_pop($stack);
    $rules = $ruleSets[$node[0]];
    $constraints = $node[1];
    $old = $node[2] . ','. $node[0] ;

    echo "Current Path : $old\n";
    echo "Current constraints : " . implode(', ', $constraints) . "\n\n";
    foreach ($rules as $rule) {
        $constraintsTrue = $constraints;

        if ($rule->param) {
            if ($rule->gt) {
                $constraintsTrue['min' . $rule->param] = max($constraintsTrue['min' . $rule->param], $rule->val + 1);
                $constraints['max' . $rule->param] = min($constraints['max' . $rule->param], $rule->val);
            } else {
                $constraintsTrue['max' . $rule->param] = min($constraintsTrue['max' . $rule->param], $rule->val -1);
                $constraints['min' . $rule->param] = max($constraints['min' . $rule->param], $rule->val);
            }
        }
        if ($rule->out == 'A') {
            $validConstraints[] = $constraintsTrue;
            continue;
        }
        if ($rule->out == 'R') {
            continue;
        }
        $stack[] = [$rule->out, $constraintsTrue, $old];
    }
}

$combinations = 0;
foreach ($validConstraints as $constraints) {
    $combinations += ($constraints['maxx'] - $constraints['minx'] + 1) *
        ($constraints['maxm'] - $constraints['minm'] + 1) *
        ($constraints['maxa'] - $constraints['mina'] + 1) *
        ($constraints['maxs'] - $constraints['mins'] + 1);
}

echo "total combinations : $combinations\n";



function applyRules($ruleName, $item): string
{
    global $ruleSets;

    foreach ($ruleSets[$ruleName] as $rule) {
        if (!$rule->param) return $rule->out;
        if (
            $rule->gt && $item[$rule->param] > $rule->val ||
            !$rule->gt && $item[$rule->param] < $rule->val
        ) return $rule->out;
    }
    return 'error';
}

class RuleParam
{

    public string $param = '';
    public bool $gt;
    public int $val;
    public string $out;

    public function __construct($rule)
    {
        if (!str_contains($rule, ':')) {
            $this->out = $rule;
            return $this;
        }

        preg_match_all('/([xmas])([<>])(\d+):(\w+)/', $rule, $matches);
        $this->param = $matches[1][0];
        $this->gt = $matches[2][0] == ">";
        $this->val = $matches[3][0];
        $this->out = $matches[4][0];
        return $this;
    }
}
