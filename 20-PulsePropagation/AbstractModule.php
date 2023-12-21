<?php

abstract class AbstractModule{

    public string $name;
    
    public array $listeners = [];
    public array $publishers = [];


    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addListener(AbstractModule $listener){
        if(!in_array($listener, $this->listeners)) $this->listeners[] = $listener;
    }

    public function addPublisher(AbstractModule $publisher){
        if(!in_array($publisher, $this->publishers)) $this->publishers[] = $publisher;
    }

    abstract public function processPulse(bool $pulse, ?AbstractModule $from, array &$queue);
    abstract public function reset();
    abstract public function getState():string;

    public function __toString():string
    {
        $string = '';
        $string .= $this->name . " : \n";
        $string .= "listeners : \n";
        foreach($this->listeners as $l){
            $string.= $l->name . ' ';
        }
        $string .= "\npublishers : \n";
        foreach($this->publishers as $l){
            $string.= $l->name . ' ';
        }
        $string .= "\n";
        return $string;
    }
}