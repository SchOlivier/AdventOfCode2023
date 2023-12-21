<?php

class ConjonctionModule extends AbstractModule
{
    public ?array $lastPulsesReceived = null;

    public function processPulse(bool $pulse, ?AbstractModule $from, array &$queue)
    {
        $this->lastPulsesReceived[$from->name] = $pulse;

        $pulseToSend = count($this->lastPulsesReceived) != count(array_filter($this->lastPulsesReceived));

        foreach ($this->listeners as $listener) {
            $queue[] =  ['from' => $this, 'to' => $listener, 'pulse' => $pulseToSend];
        }
    }

    private function initLastPulses()
    {
        foreach ($this->publishers as $publisher) {
            $this->lastPulsesReceived[$publisher->name] = false;
        }
    }
    
    public function getState(): string
    {
        $state = $this->name;
        foreach($this->lastPulsesReceived as $pulse){
            $state .= $pulse ? '1' : '0';
        }
        return '';
    }
    public function reset(){
        $this->initLastPulses();
    }
}
