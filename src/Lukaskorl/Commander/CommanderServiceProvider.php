<?php namespace Lukaskorl\Commander;

use Illuminate\Support\ServiceProvider;

class CommanderServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Register the default implementation for the command bus
        $this->app->bind('Lukaskorl\Commander\CommandBus', 'Lukaskorl\Commander\CommandBus\ExecutionCommandBus');

        // Register the default implementation for name inflections
        $this->app->bind('Lukaskorl\Commander\CommandNameInflector', 'Lukaskorl\Commander\Inflector\GroupedNamespaceCommandNameInflector');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
