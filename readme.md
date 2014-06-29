# Commander
A an implementation of the _Domain Events_ pattern for Laravel. Many of the basic ideas of this package originate in an article from [Chris Fidao](http://fideloper.com/hexagonal-architecture).

  1. [Abstract and introduction](#intro)
  1. [Decorating the command bus](#decorator)
  1. [License](#license)

## <a name="intro"></a>Abstract and introduction

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

  * At the boundary between _application layer_ and _business logic_ **commands** (this are basically DTOs) are passed in from the application to the business logic. Typically the controllers will generate the commant and execute them on a **command bus**.
  * A **command** basically represents a business use-case and defines a way how we want the outside to interact with our domain.
  * The **command bus** will forward commands to the appropriate **command handler**.
  * The _interface_ of the command bus is part of the **domain logic** whereas the _implementation_ of the command bus is part of the **application** because is makes use of functionality provided by the framework or vendor libraries.
  * A best practice is to decouple vendor libraries used by the application layer by the use of _interfaces_. So the application layer defines how it will use the framework functionality without coupling directly to the framework.
  * A **command handler** executes a given command and runs the logic to fulfill the business value of the use-case. This is where reusage kicks in. **Command handlers** may receive commands from any **command bus** (used by CLI, HTTP, ...).

## <a name="decorator"></a>Decorating the command bus

A **command bus** has a very simple interface (namely an _execute(...)_ method). it is easy to decorate that interface and let the decorator add functionality to the **command bus**.

	$commandBus = new ValidationCommandBus(new LogCommandBus(new ExecutionCommandBus));

Each of the custom command buses overwrites the _execute(...)_ method to inject custom logic before or after executing _parent::execute(...)_. 


## <a href="license"></a>License

_Laravel Crafter_ is distributed under [MIT](http://opensource.org/licenses/MIT) license.