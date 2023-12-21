<?php

class DummyModule extends AbstractModule
{
    public function processPulse(bool $pulse, ?AbstractModule $from, array &$queue)
    {
    }

    public function getState(): string
    {
        return $this->name;
    }

    public function reset(){}
}
