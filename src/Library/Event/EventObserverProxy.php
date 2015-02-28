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
 * Class EventObserverProxy
 *
 * This proxy class will try to always construct an object
 * compliant with the `ObserverInterface`. It uses a simple
 * `callback` property that can be:
 *
 * -    a "real" `ObserverInterface` object
 * -    another object with a `handleEvent( EventInterface $event )` method
 * -    an array like `array( class , method )` to call a specific method
 * -    a closure to call when the event is fired
 *
 */
class EventObserverProxy
    implements ObserverInterface
{

    /**
     * @var null|callable
     */
    protected $callback = null;

    /**
     * @param callable|null $callback
     */
    public function __construct($callback = null)
    {
        if (!is_null($callback)) {
            $this->setCallback($callback);
        }
    }

    /**
     * @param callable|array $callback
     * @return $this
     * @throws \Exception
     */
    public function setCallback($callback)
    {
        if (
            !is_object($callback) && !is_callable($callback) && !(
                is_array($callback) &&
                count($callback)==2 &&
                is_object($callback[0]) &&
                is_callable(array(
                    get_class($callback[0]), $callback[1]
                ))
            )
        ) {
            throw new \Exception(
                sprintf('An event observer callback must be callable (got "%s")!', gettype($callback))
            );
        }
        $this->callback = $callback;
        return $this;
    }

    /**
     * @param EventInterface $event
     * @return mixed|null
     * @throws \Exception
     */
    public function handleEvent(EventInterface $event)
    {
        $return = null;
        if (!is_null($this->callback)) {
            try {
                if (is_object($this->callback) && !($this->callback instanceof \Closure)) {
                    if (method_exists($this->callback, 'handleEvent')) {
                        $return = call_user_func(array($this->callback, 'handleEvent'), $event);
                    } else {
                        throw new \Exception(
                            sprintf('An event observer must define a "handleEvent($event)" method (for class "%s")!', get_class($this->callback))
                        );
                    }
                } else {
                    $return = call_user_func($this->callback, $event);
                }
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return $return;
    }

}

// Endfile