<?php namespace Lukaskorl\Commander\Inflector;

use Lukaskorl\Commander\Command;
use Lukaskorl\Commander\CommandNameInflector;

class SameNamespaceCommandNameInflector implements CommandNameInflector {

    public function toHandlerClassname(Command $command)
    {
        return str_replace('Command', 'CommandHandler', get_class($command));
    }
}