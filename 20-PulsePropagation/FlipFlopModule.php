<?php

class FlipFlopModule extends AbstractModule
{
    public bool $state = false; // on/off

    public function processPulse(bool $pulse, ?AbstractModule $from, array &$queue)
    {
        if ($pulse) return;

        $this->state = !$this->state;
        foreach ($this->listeners as $listener) {
            $queue[] = ['from' => $this, 'to' => $listener, 'pulse' => $this->state];
        }
    }

    public function getState(): string
    {
        return $this->name . ($this->state ? '1' : '0');
    }

    public function reset()
    {
        $this->state = false;
    }
}
