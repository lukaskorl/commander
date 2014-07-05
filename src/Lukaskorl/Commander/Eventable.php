<?php namespace Lukaskorl\Commander;

trait Eventable {

    protected $pendingEvents = [];

    public function flushEvents()
    {
        $events = $this->pendingEvents;

        $this->pendingEvents = [];

        return $events;
    }

    protected function raise(Event $event)
    {
        $this->pendingEvents[] = $event;

        return $this;
    }
} 