<?php namespace Lukaskorl\Commander\Inflector;

interface CommandNameInflector {

    public function toHandlerClassname(Command $command);

} 