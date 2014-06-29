<?php namespace Lukaskorl\Commander;

interface CommandNameInflector {

    public function toHandlerClassname(Command $command);

} 