<?php

class BroadcastModule extends AbstractModule
{
    public function processPulse(bool $pulse, ?AbstractModule $from, array &$queue)
    {
        foreach ($this->listeners as $listener) {
            $queue[] = ['from' => $this, 'to' => $listener, 'pulse' => $pulse];
        }
    }

    public function getState(): string
    {
        return 'broadcaster';
    }

    public function reset()
    {
    }
}
