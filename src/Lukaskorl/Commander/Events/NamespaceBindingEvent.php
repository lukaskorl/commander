<?php namespace Lukaskorl\Commander\Events;


trait NamespaceBindingEvent {

    /**
     * Dot-object notation for event binding name derived from
     * full PHP namespace.
     *
     * @return string
     */
    public function name()
    {
        return str_replace('\\', '.', get_class($this));
    }

} 