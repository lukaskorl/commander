<?php namespace Lukaskorl\Commander;

use Lukaskorl\Commander\Dispatcher\EventDispatcher;
use App;

trait Dispatchable {

    /**
     * Dispatch and flush all events for an event raising object
     *
     * @param Eventable $raiser
     * @return mixed
     */
    public function dispatchAndFlushEventsFor($raiser)
    {
        return $this->getEventDispatcher()->dispatch($raiser->flushEvents());
    }

    /**
     * Instantiate the event dispatcher
     *
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return App::make('Lukaskorl\Commander\Dispatcher\EventDispatcher');
    }

} 