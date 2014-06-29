<?php namespace Lukaskorl\Commander\CommandBus;

use Illuminate\Container\Container;
use Lukaskorl\Commander\CommandHandler;
use Lukaskorl\Commander\CommandNameInflector;

class ExecutionCommandBus implements CommandBus {

    /** @var Container */
    private $container;

    /** @var CommandNameInflector */
    private $inflector;

    public function __construct(Container $container, CommandNameInflector $inflector)
    {
        $this->container = $container;
        $this->inflector = $inflector;
    }

    public function execute(Command $command)
    {
        return $this->getCommandHandler($command)->handle($command);
    }

    /**
     * @param Command $command
     * @return CommandHandler
     */
    protected function getCommandHandler(Command $command)
    {
        $handlerClassname = $this->inflector->toHandlerClassname($command);

        return $this->container->make($handlerClassname);
    }
}