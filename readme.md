# Commander
A an implementation of the _Domain Events_ pattern for Laravel. Many of the basic ideas of this package originate in an article from [Chris Fidao](http://fideloper.com/hexagonal-architecture).

  1. [Introduction](#intro)
  1. [Installation](#install)
  1. [Firing a command](#commands)
  1. [Handling a command](#handlers)
  1. [Decorating the command bus](#decorator)
  1. [License](#license)

## <a name="intro"></a> Introduction

_Commander_ provides you with a programming pattern best known from Hexagonal Architecture (I like to call it **Multigonal Architecture** because in my opinion it is more appropriate than limiting the number of sides to 6 and allows me to add some of my own _best practices_) that tries to achieve:

  * your application to be driven by multiple user or automated input sources (HTTP, CLI, tests, background queues, ...)
  * to cleanly decouple your business logic from the framework of your choice
  * to decouple data persistence from business logic

The most important principles that drive the architecture of _Commander_ are consolidated in [SOLID](http://en.wikipedia.org/wiki/SOLID_(object-oriented_design)) oriented design - especially the _SRP_ ([Single responsibility principle](http://en.wikipedia.org/wiki/Single_responsibility_principle)).

### Layers of Multigonal Architecture

This architecture is represented by concentric polygons:

  * _innermost_: The **Business Domain** contains the custom logic that represents your business use-cases.
  * _middle_: The **Application** ties together entities found in the domain layer with a request that came in through the framework. A core functionality in _Commander_ is dispatching the **Domain Events** raised in the domain layer.
  * _outermost_: **Framework** contains all the code that it usually provided by your framework or external third-party libraries but that is not part of your application.

### Interactions between layers

  * At the boundary between _application layer_ and _business logic_ **commands** (this are basically DTOs) are passed in from the application to the business logic. Typically the controllers will generate the command and execute them on a **command bus**.
  * A **command** basically represents a business use-case and defines a way how we want the outside to interact with our domain.
  * The **command bus** will forward commands to the appropriate **command handler**.
  * The _interface_ of the command bus is part of the **domain logic** whereas the _implementation_ of the command bus is part of the **application** because is makes use of functionality provided by the framework or vendor libraries.
  * A best practice is to decouple vendor libraries used by the application layer by the use of _interfaces_. So the application layer defines how it will use the framework functionality without coupling directly to the framework.
  * A **command handler** executes a given command and runs the logic to fulfill the business value of the use-case. This is where reusage kicks in. **Command handlers** may receive commands from any **command bus** (used by CLI, HTTP, ...).

## <a name="install"></a>Installation

To install _Commander_ via composer execute the following command in your applications root directory:

	$ composer require lukaskorl/commander

and specify a version number of `0.*` to use the most current version and automatically include all compatible updates and bugfixes.

To enable configuration and doing some automatic bootstrapping of your Laravel application add the service provider to your `app/config/app.php` configuration file:

	<?php
		...
		'providers' => array(

			...
			'Lukaskorl\Commander\CommanderServiceProvider',
		),
		...

## <a name="commands"></a>Firing a command

The best way to think of commands is to think of them as a business use-case. So create one command per unique use-case by implementing the `Lukaskorl\Commander\Command` interface.

	<?php namespace MyApplication\Student;
	
	use Lukaskorl\Commander\Command;

	class EnrollStudentCommand implements Command {

		public $studentId;
		public $courseId;

		public function __construct($studentId, $courseId)
		{
			$this->studentId = $studentId;
			$this->courseId = $courseId;
		}

	}

This gives you the chance to fully customize the command class. Your commands should not be more than simple DTOs (_Data Transfer Objects_). _Commander_ provides a flexible base class for DTO commands. Just extend your command from `Lukaskorl\Commander\Commands\DataTransferCommand`.

	<?php namespace MyApplication\Student;

	use Lukaskorl\Commander\Commands\DataTransferCommand;

	class EnrollStudentCommand extends DataTransferCommand {
	}

The DTO base class let's you pass in an associative array of data into the constructor which can then be accessed as properties of the object.

After creating your command you will have to execute it. The execution of a command is handled by a command bus. _Commander_ comes with a basic implementation which will just execute the command. To use this implementation create the command bus and call `execute(...)` while passing in the command. If you are using [Laravel 4](http://laravel.com/docs) you will most likely call the execution from your controller. To do so inject the command bus into your controller:

	<?php

	use Lukaskorl\Commander\CommandBus\ExecutionCommandBus;
	use MyApplication\Student\EnrollStudentCommand;

	class HomeController extends BaseController {

		protected $commandBus;

		public function __construct(ExecutionCommandBus $commandBus)
		{
			$this->commandBus = $commandBus;
		}

		public function yourAction()
		{
			$this->commandBus->execute(
				new EnrollStudentCommand(Input::all());
			);
		}

	}

## <a name="handlers"></a>Handling a command

An inflector will transform the class of the command in the class of the command handler. The default inflector will transform a command class of `MyApplication\Thing\DoSomethingCommand` into `MyApplication\Thing\DoSomethingCommandHandler`.  You can change this behavior by implementing a custom `CommandNameInflector` and binding this class to [Laravel's IoC container](http://laravel.com/docs/ioc).

	$this->app->bind('Lukaskorl\Commander\Inflector\CommandNameInflector', 'MyApplication\Inflector\CustomCommandNameInflector');

To continue with the above example of the `MyApplication\Student\EnrollStudentCommand` a corresponding command handler is created by implementing the `Lukaskorl\Commander\CommandHandler` interface:

	<?php namespace MyApplication\Student;

	class EnrollStudentCommandHandler implements CommandHandler {

		public function handle(Command $command)
		{
			// ... do stuff ...
		}

	}

Put your handler code into the `handle(...)` method. That's it, now your command bus will instantiate the corresponding handler and execute the command.

## <a name="listeners"></a>Listening to events

_Commander_ ships with a convenient base class for your listeners which introduces a very readable method convention. Simply extend your listener from `Lukaskorl\Commander\EventListener`. For each event you want to handle create a `when<NameOfEvent>` method. The method accepts the `Event` itself as the first and only parameter. i.e.

	<?php namespace MyApplication\Listeners;
	
	use Lukaskorl\Commander\EventListener;
	use Lukaskorl\Commander\Event;

	class EmailNotifier extends EventListener {

		public function whenUserHasBeenRegister(Event $event)
		{
			// Send welcome email
		}

		public function whenUserHasUnsubscribed(Event $event)
		{
			// Send good-bye email
		}


	}

__After creating your listener you will have to register the listener at the dispatcher.__

_Commander_ provides a configuration for automatically registering any number of event listeners for a given event. It is very likely that you may want to change this configuration. To do so you will have to publish the package configuration:

	$ php artisan config:publish lukaskorl/commander

This will put the `listeners.php` configuration file in `app/config/packages/lukaskorl/commander`. The configuration provides an exmaple listener. Feel free to change this configuration to your needs.

i.e. if you have a `MyApplication\User\UserHasBeenRegisteredEvent` and want to register the listeners `MyApplication\Listeners\EmailNotifier` and `MyApplication\Listeners\Logging` for this event your configuration will look like this:

	<?php return [
		...

		'MyApplication.User.*' => [
			'MyApplication\Listeners\EmailNotifier',
			'MyApplication\Listeners\Logging',
		],

		...
	];

This will trigger your listeners on any event in the `MyApplication\User` namespace. If you extend your listener from `Lukaskorl\Commander\EventListener` only those events that have a corresponding `when<NameOfEvent>` method will be handled.

If you want to manually register listeners on the dispatcher you can simply inject `Lukaskorl\Commander\Dispatcher\EventDispatcher` into your class or use the following snippet:

	/** @var Lukaskorl\Commander\Dispatcher\EventDispatcher $dispatcher */
    $dispatcher = App::make('Lukaskorl\Commander\Dispatcher\EventDispatcher');
    $dispatcher->registerListener('Acme.*', 'MyApplication\Listeners\Notification');

## <a name="decorator"></a>Decorating the command bus

A **command bus** has a very simple interface (namely an _execute(...)_ method). it is easy to decorate that interface and let the decorator add functionality to the **command bus**.

	$commandBus = new ValidationCommandBus(new LogCommandBus(new ExecutionCommandBus));

Each of the custom command buses overwrites the _execute(...)_ method to inject custom logic before or after executing _parent::execute(...)_. 


## <a href="license"></a>License

_Laravel Crafter_ is distributed under [MIT](http://opensource.org/licenses/MIT) license.