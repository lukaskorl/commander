<?php namespace Lukaskorl\Commander;

interface Event {

    /**
     * Return the binding name for the event
     * @return string
     */
    public function name();

} 