<?php namespace Lukaskorl\Commander;

interface CommandBus {

    public function execute(Command $command);

} 