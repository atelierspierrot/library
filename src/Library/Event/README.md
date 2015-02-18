Event management
================


SPL interfaces
--------------

As a reminder, the [SPL observer objects](http://php.net/manual/en/spl.misc.php)
are:

```php
interface SplObserver {
    abstract public void update ( SplSubject $subject )
}

interface SplSubject {
    abstract public void attach ( SplObserver $observer )
    abstract public void detach ( SplObserver $observer )
    abstract public void notify ( void )
}
```


Basics
------

The basic idea of an *event propagation* system in PHP is quite simple:

-   an object `A` can trigger some events during its life-cycle (to inform
    about an update for instance) ;
-   an object `B` can adapt its properties or behaviors when the object `A`
    triggered an event (to keep the last value of a property of `A` for instance).

This can be implemented with an *observer/observable* system where the object `A`
of the example above is *observable* while the `B` is its *observer*. Basically,
the *observable* can be considered as an `SplSubject` as defined in the SPL interfaces
above while the *observer* can be considered as an `SplObserver`. But the standard
system (the SPL interfaces) only allows one single event to be triggered (or multiple
events handled by the same method of the observer). We could use this basic definitions
of event triggering but it forces the implementations to make a `switch` if the observable
can trigger different events ...

The implementation in the library is ready to handle multiple events triggering
allowing the observers to define the method that should be called when an event is
fired. This method defaults to `handleEvent()`. To do so, the `SplObserver` interface 
is renamed (and rewritten) as the `ObserverInterface` and the `SplSubject` 
as the `ObservableInterface`. The event is a standalone object implementing the
`EventInterface`:

```php
interface ObserverInterface {
    abstract public void handleEvent ( EventInterface $event )
}

interface ObservableInterface {
    abstract public void attachObserver ( ObserverInterface $observer | array($object , $method) | $callback )
    abstract public void detachObserver ( ObserverInterface $observer | array($object , $method) | $callback )
    abstract public void triggerEvent ( $event_name )
}

interface EventInterface {
    abstract public ObservableInterface getSubject ()
    abstract public string getName ()
    abstract public void stopPropagation ()
    abstract public bool isPropagationStopped ()
}
```


Events manager
--------------

A kernel object is defined to handle a set of events listeners/observers
for a global application: the `EventManager` object. It introduces two new
concepts: the *listeners* and the *subscribers*.

Basically, a *listener* is like an observer, it references itself to the
object triggering the event it want to observe, a *subscriber* does not
references itself to each events it wants to listen, but to the global
events manager, that asks it the list of events it may listen:

```php
interface EventListenerInterface 
    extends ObserverInterface {}

interface EventSubscriberInterface {
    abstract public array getSubscribedEvents ()
}
```

