<?php namespace Lukaskorl\Commander;

use Illuminate\Support\ServiceProvider;
use Lukaskorl\Commander\Dispatcher\EventDispatcher;

class CommanderServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('lukaskorl/commander');

        // Event related bindings (we do this in boot() because we need access to configuration files)
        $this->bindEventDispatcherToContainer($this->app);
        $this->registerEventListenersFromConfig();
    }

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
        $this->app->bind('Lukaskorl\Commander\Inflector\CommandNameInflector', 'Lukaskorl\Commander\Inflector\GroupedNamespaceCommandNameInflector');
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

    protected function registerEventListenersFromConfig()
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->app->make('Lukaskorl\Commander\Dispatcher\EventDispatcher');
        foreach ($this->app['config']->get('commander::listeners') as $binding => $listeners) {
            foreach ($listeners as $listener) {
                $dispatcher->registerListener($binding, $listener);
            }
        }
    }

    protected function bindEventDispatcherToContainer($app)
    {
        $config = $app['config'];
        if ($config->get('commander::dispatcher.singleton') === true) {
            $app->singleton('Lukaskorl\Commander\Dispatcher\EventDispatcher', function () use ($app, $config) {
                return $app->make($config->get('commander::dispatcher.implementation'));
            });
        } else {
            $app->bind('Lukaskorl\Commander\Dispatcher\EventDispatcher', $config->get('commander::dispatcher.implementation'));
        }
    }

}
