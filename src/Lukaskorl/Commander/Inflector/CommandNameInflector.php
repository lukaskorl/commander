<?php namespace Lukaskorl\Commander\Inflector;

use Lukaskorl\Commander\Command;

interface CommandNameInflector {

    public function toHandlerClassname(Command $command);

} 