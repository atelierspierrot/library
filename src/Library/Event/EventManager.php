<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/library>.
 */

namespace Library\Event;

/**
 * Class EventManager
 *
 * This is a global event handling manager. It will store
 * all *listeners* and *subscribers* for specific events
 * in an instance of `EventObserverStorage` and trigger
 * the observers methods when an event is fired.
 *
 */
class EventManager
    implements EventManagerInterface
{

    /**
     * @var EventObserverStorage
     */
    protected $_observers_storage;

    /**
     * Initialize the observers storage registry
     */
    public function __construct()
    {
        $this->_observers_storage = new EventObserverStorage();
    }

    /**
     * @param $event
     * @param $callback
     * @return $this
     * @throws \Exception
     */
    public function addListener($event, $callback)
    {
        if (!isset($this->_observers_storage[$event])) {
            $this->_observers_storage[$event] = new EventObserverStorage();
        }
        $this->_observers_storage[$event]->add($callback);
        return $this;
    }

    /**
     * @param $event
     * @param $callback
     * @return $this
     * @throws \Exception
     */
    public function removeListener($event, $callback)
    {
        if (isset($this->_observers_storage[$event])) {
            $this->_observers_storage[$event]->remove($callback);
        }
        return $this;
    }

    /**
     * @param $event
     * @return bool
     */
    public function hasListeners($event)
    {
        return (bool) (isset($this->_observers_storage[$event]) && count($this->_observers_storage[$event]));
    }

    /**
     * @param $event
     * @return array|null
     */
    public function getListeners($event)
    {
        if ($this->hasListeners($event)) {
            return $this->_observers_storage[$event];
        }
        return null;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @return $this
     * @throws \Exception
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $listeners = $subscriber->getSubscribedEvents();
        foreach ($listeners as $event => $listener) {
            try {
                $this->addListener($event, array($subscriber, $listener));
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return $this;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @return $this
     * @throws \Exception
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $listeners = $subscriber->getSubscribedEvents();
        foreach ($listeners as $event => $listener) {
            try {
                $this->removeListener($event, array($subscriber, $listener));
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return $this;
    }

    /**
     * @param string $event_name
     * @param ObservableInterface $subject
     * @return $this
     */
    public function triggerEvent($event_name, ObservableInterface $subject)
    {
        if (is_null($event_name)) {
            return $this;
        }
        $event = new Event($subject, $event_name);
        if (isset($this->_observers_storage[$event_name])) {
            foreach ($this->_observers_storage[$event_name] as $observer) {
                if ($event->isPropagationStopped()) {
                    break;
                }
                /* @var EventObserverProxy $observer */
                $observer->handleEvent($event);
            }
        }
        return $this;
    }

}

// Endfile