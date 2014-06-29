<?php namespace Lukaskorl\Commander;

interface CommandHandler {

    public function handle(Command $command);

} 