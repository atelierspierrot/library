#!/usr/bin/env php
<?php

#############
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_STRICT);
require_once __DIR__.'/../src/SplClassLoader.php';
$classLoader = new SplClassLoader('Library', __DIR__.'/../src');
$classLoader->register();
#############

//$n = isset($argv[1]) ? $argv[1] : '0';

function writeln($line_in) {
    echo $line_in.PHP_EOL;
}


class TestObservable extends \Library\Event\AbstractObservable implements \Library\Event\ObservableInterface {

    public function __construct($name = 'Anonymous')
    {
        parent::__construct();
        $this->setName($name);
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

}

class TestEventListener implements \Library\Event\ObserverInterface {

    public function handleEvent(\Library\Event\EventInterface $event)
    {
        writeln('['.get_called_class().']'
            .' :: triggering event '.$event->getName()
            .' :: name has changed to '.$event->getSubject()->name
        );
    }

}

class TestEventListenerBis extends TestEventListener {}

class TestEventListenerStopper extends TestEventListener {

    public function handleEvent(\Library\Event\EventInterface $event)
    {
        parent::handleEvent($event);
        $event->stopPropagation();
    }

}

class TestEventListenerCallback {

    public function myMethod(\Library\Event\EventInterface $event)
    {
        writeln('['.get_called_class().']'
            .' :: triggering event '.$event->getName()
            .' :: name has changed to '.$event->getSubject()->name
        );
    }

}

class TestEventListenerError {

    public function handleEvent()
    {
        writeln('['.get_called_class().']'
            .' :: must not throw an error ...'
        );
    }

}

class TestSubscriber implements \Library\Event\EventSubscriberInterface {

    public static function getSubscribedEvents()
    {
        return array(
            'my.event.1'=>'event1',
            'my.event.2'=>'event2',
        );
    }

    public function event1(\Library\Event\EventInterface $event)
    {
        writeln('['.__CLASS__.'] ::event1 :: name has changed to '.$event->getSubject()->name);
    }

    public function event2(\Library\Event\EventInterface $event)
    {
        writeln('['.__CLASS__.'] ::event2 :: name has changed to '.$event->getSubject()->name);
    }

}

class TestSubscriberError implements \Library\Event\EventSubscriberInterface {

    public static function getSubscribedEvents()
    {
        return array(
            'my.event.3'=>'event3'
        );
    }

    protected function event3(\Library\Event\EventInterface $event)
    {
        writeln('['.__CLASS__.'] ::event3 :: name has changed to '.$event->getSubject()->name);
    }

}

writeln('BEGIN OF TEST SET 1');
writeln('');

writeln('- classic observer');
$obj = new TestObservable();
$listener1 = new TestEventListener();
$obj
    ->setName('name 1')
    ->attachObserver($listener1)
    ->triggerEvent()
;

writeln('- add a second classic observer');
$listener2 = new TestEventListenerBis();
$obj
    ->setName('name 2')
    ->attachObserver($listener2)
    ->triggerEvent()
;
writeln('- detach first classic observer');
$obj
    ->setName('name 3')
    ->detachObserver($listener1)
    ->triggerEvent()
;

writeln('- add an observer as a Closure');
$listener3 = function($event){writeln('[closure]'
    .' :: triggering event '.$event->getName()
    .' :: name has changed to '.$event->getSubject()->name
);};
$obj
    ->setName('name 4')
    ->attachObserver($listener3)
    ->triggerEvent()
;
writeln('- detach the Closure');
$obj
    ->setName('name 5')
    ->detachObserver($listener3)
    ->triggerEvent()
;

writeln('- add an observer as a dynamic function');
$obj
    ->setName('name 6')
    ->attachObserver(function($event){writeln('[dynamic callback]'
        .' :: triggering event '.$event->getName()
        .' :: name has changed to '.$event->getSubject()->name
    );})
    ->triggerEvent()
;

writeln('- add an observer as a callback object method');
$listener4 = new TestEventListenerCallback();
$obj
    ->setName('name 7')
    ->attachObserver(array($listener4, 'myMethod'))
    ->triggerEvent()
;
writeln('- detach the callback object method');
$obj
    ->setName('name 7')
    ->detachObserver(array($listener4, 'myMethod'))
    ->triggerEvent()
;

writeln('');
writeln('END OF TEST SET 1');
writeln('');
writeln('BEGIN OF TEST SET 2');
writeln('');


writeln('- classic observer that stops propagation');
$obj = new TestObservable();
$listener1 = new TestEventListener();
$listener2 = new TestEventListenerStopper();
$obj
    ->setName('name 1')
    ->attachObserver($listener2)
    ->attachObserver($listener1)
    ->triggerEvent()
;

writeln('- detach the observer that stops propagation');
$obj
    ->setName('name 2')
    ->detachObserver($listener2)
    ->triggerEvent()
;

writeln('- classic observer that stops propagation as second observer');
$obj = new TestObservable();
$obj
    ->setName('name 3')
    ->attachObserver($listener1)
    ->attachObserver($listener2)
    ->triggerEvent()
;

writeln('- attach an observer that does not have the "event" argument');
$listener5 = new TestEventListenerError();
$obj = new TestObservable();
$obj
    ->setName('name 4')
    ->attachObserver($listener5)
    ->triggerEvent()
;

writeln('');
writeln('END OF TEST SET 2');
writeln('');
writeln('BEGIN OF TEST SET 3');
writeln('');

writeln('- attaching eventListeners to eventManager');
$obj = new TestObservable();
$listener1 = new TestEventListener();
$listener2 = new TestEventListenerBis();
$manager = new \Library\Event\EventManager();
$manager
    ->addListener('my.event.1', $listener1)
    ->addListener('my.event.2', $listener1)
    ->addListener('my.event.2', $listener2)
;

writeln('- triggering event my.event.1');
$obj->setName('name 1');
$manager->triggerEvent('my.event.1', $obj);

writeln('- triggering event my.event.2');
$obj->setName('name 2');
$manager->triggerEvent('my.event.2', $obj);

writeln('- attaching subscriber');
$manager->addSubscriber(new TestSubscriber());

writeln('- triggering event my.event.2');
$obj->setName('name 3');
$manager->triggerEvent('my.event.2', $obj);

$listener3 = function($event){writeln('[closure]'
    .' :: triggering event '.$event->getName()
    .' :: name has changed to '.$event->getSubject()->name
);};
$listener4 = new TestEventListenerCallback();
$manager
    ->addListener('my.event.4', $listener3)
    ->addListener('my.event.4', array($listener4, 'myMethod'))
    ->addListener('my.event.4', function($event){writeln('[dynamic callback]'
        .' :: triggering event '.$event->getName()
        .' :: name has changed to '.$event->getSubject()->name
    );})
;
$obj->setName('name 4');
$manager->triggerEvent('my.event.4', $obj);


writeln('');
writeln('END OF TEST SET 3');

#############
exit(PHP_EOL.'-- endrun --'.PHP_EOL);
