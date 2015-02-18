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

use \Library\Helper\Code as CodeHelper;

/**
 * Class AbstractObservable
 */
abstract class AbstractObservable
    implements ObservableInterface
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
     * @param ObserverInterface|array|callable $observer | array($object , $method) | $callback
     * @return $this
     */
    public function attachObserver($observer)
    {
        $this->_observers_storage->add($observer);
        return $this;
    }

    /**
     * @param ObserverInterface|array|callable $observer | array($object , $method) | $callback
     * @return $this
     */
    public function detachObserver($observer)
    {
        $this->_observers_storage->remove($observer);
        return $this;
    }

    /**
     * @param string|null $event_name
     * @return $this
     */
    public function triggerEvent($event_name = null)
    {
        if (is_null($event_name)) {
            $event_name = CodeHelper::getPropertyName(get_class($this));
        }
        $event = new Event($this, $event_name);
        foreach ($this->_observers_storage as $observer) {
            if ($event->isPropagationStopped()) {
                break;
            }
            /* @var EventObserverProxy $observer */
            $observer->handleEvent($event);
        }
        return $this;
    }

}

// Endfile