<?php namespace Lukaskorl\Commander;

use App;

trait CommandExecutor {

    /**
     * Execute a command
     *
     * @param Command $command
     * @return mixed
     */
    protected function execute(Command $command)
    {
        return $this->getCommandBus()->execute($command);
    }

    /**
     * Instantiate the command bus
     *
     * @return CommandBus
     */
    protected function getCommandBus()
    {
        return App::make('Lukaskorl\Commander\CommandBus');
    }

}
