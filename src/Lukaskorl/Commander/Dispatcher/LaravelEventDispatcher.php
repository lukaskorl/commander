<?php namespace Lukaskorl\Commander\Dispatcher;

use Illuminate\Events\Dispatcher;
use Illuminate\Log\Writer;
use Lukaskorl\Commander\Event;

class LaravelEventDispatcher implements EventDispatcher {

    /** @var Dispatcher */
    private $dispatcher;

    /** @var Writer */
    private $log;

    function __construct(Dispatcher $dispatcher, Writer $log)
    {
        $this->dispatcher = $dispatcher;
        $this->log = $log;
    }

    public function dispatch(array $events)
    {
        foreach ($events as $event) {
            /** @var Event $event */
            $this->dispatcher->fire($event->name(), $event);

            $this->log->info(__CLASS__.": [{$event->name()}] fired");
        }
    }

    public function registerListener($binding, $listener)
    {
        $this->dispatcher->listen($binding, $listener);
    }
}