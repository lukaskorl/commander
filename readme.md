# Commander
A an implementation of the _Domain Events_ pattern for Laravel. Many of the basic ideas of this package originate in an article from [Chris Fidao](http://fideloper.com/hexagonal-architecture).

## Abstract

_Commander_ provides you with a programming pattern best known from Hexagonal Architecture (I like to call it **Multigonal Architecture** because in my opinion it is more appropriate than limiting the number of sides to 6 and allows me to add some of my own _best practices_) that tries to achieve:

  - your application to be driven by multiple user or automated input sources (HTTP, CLI, tests, background queues, ...)
  - to cleanly decouple your business logic from the framework of your choice
  - to decouple data persistence from business logic

The most important principles that drive the architecture of _Commander_ are consolidated in [SOLID](http://en.wikipedia.org/wiki/SOLID_(object-oriented_design)) oriented design - especially the _SRP_ ([Single responsibility principle](http://en.wikipedia.org/wiki/Single_responsibility_principle)).

