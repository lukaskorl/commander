<?php namespace Lukaskorl\Commander\Dispatcher;

interface EventDispatcher {

    public function dispatch(array $events);

    public function registerListener($binding, $listener);

} 