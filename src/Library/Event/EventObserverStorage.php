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
 * Class EventObserverStorage
 *
 * This will handle a collection of event observers all wrapped
 * in an `EventObserverProxy` object and indexed with a special
 * hash allowing to retrieve a defined observer.
 *
 */
class EventObserverStorage
    extends \ArrayObject
{

    /**
     * @param ObserverInterface|array|callable $observer | array($object , $method) | $callback
     * @return $this
     */
    public function add($observer)
    {
        $safe_observer = new EventObserverProxy($observer);
        $id = $this->_getObjectHash($observer, $safe_observer);
        if (!$this->offsetExists($id)) {
            $this->offsetSet($id, $safe_observer);
        }
        return $this;
    }

    /**
     * @param ObserverInterface|array|callable $observer | array($object , $method) | $callback
     * @return $this
     */
    public function remove($observer)
    {
        $id = $this->_getObjectHash($observer);
        if ($this->offsetExists($id)) {
            $this->offsetUnset($id);
        }
        return $this;
    }

    /**
     * @param object|array|\Closure $callback
     * @param null|object $callback_proxy
     * @return string
     */
    protected function _getObjectHash($callback, $callback_proxy = null)
    {
        if (is_object($callback)) {
            return spl_object_hash($callback);
        } elseif (is_array($callback) && is_object($callback[0])) {
            return spl_object_hash($callback[0]) . $callback[1];
        } elseif (!is_null($callback_proxy)) {
            return spl_object_hash($callback_proxy);
        } else {
            return uniqid();
        }
    }

}

// Endfile