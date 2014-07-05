<?php namespace Lukaskorl\Commander;


abstract class EventListener {

    public function handle(Event $event)
    {
        if ($listenerMethod = $this->getRegisteredListener($event)) {
            return call_user_func([$this, $listenerMethod], $event);
        }
    }

    protected function getRegisteredListener($event)
    {
        $eventShortname = $this->getEventShortname($event);

        $method = "when{$eventShortname}";

        if (method_exists($this, $method)) {
            return $method;
        }

        return false;
    }

    protected function getEventShortname(Event $event)
    {
        $bindingNamespaces = explode('.', $event->name());
        return array_pop($bindingNamespaces);
    }

} 