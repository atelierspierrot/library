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
 * Class EventManagerExtended
 *
 * This only defines aliases to use event handling with facilities:
 *
 *      $eventManager
 *
 *          ->listen ( event , callback )
 *          ->stopListen ( event , callback )
 *
 *          ->subscribe ( event , subscriber )
 *          ->stopSubscribe ( event , subscriber )
 *
 *          ->on ( event , callback )
 *          ->off ( event , callback )
 *
 *          ->trigger ( event , subject )
 *
 * All aliases return the object itself for method chaining and
 * will throw any caught exception.
 *
 */
class EventManagerExtended
    extends EventManager
{

// ---------------------------
// Aliases
// ---------------------------

    /**
     * @param $event
     * @param $callback
     * @return $this
     * @throws \Exception
     */
    public function listen($event, $callback)
    {
        try {
            $this->addListener($event, $callback);
        } catch (\Exception $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * @param $event
     * @param $callback
     * @return $this
     * @throws \Exception
     */
    public function stopListen($event, $callback)
    {
        try {
            $this->removeListener($event, $callback);
        } catch (\Exception $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @return $this
     * @throws \Exception
     */
    public function subscribe(EventSubscriberInterface $subscriber)
    {
        try {
            $this->addSubscriber($subscriber);
        } catch (\Exception $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @return $this
     * @throws \Exception
     */
    public function stopSubscribe(EventSubscriberInterface $subscriber)
    {
        try {
            $this->removeSubscriber($subscriber);
        } catch (\Exception $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * @param $event
     * @param $callback
     * @throws \Exception
     * @return $this
     */
    public function on($event, $callback)
    {
        try {
            $this->addListener($event, $callback);
        } catch (\Exception $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * @param $event
     * @param $callback
     * @throws \Exception
     * @return $this
     */
    public function off($event, $callback)
    {
        try {
            $this->removeListener($event, $callback);
        } catch (\Exception $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * @param string $event
     * @param ObservableInterface $subject
     * @throws \Exception
     * @return $this
     */
    public function trigger($event, ObservableInterface $subject)
    {
        try {
            $this->triggerEvent($event, $subject);
        } catch (\Exception $e) {
            throw $e;
        }
        return $this;
    }

}

// Endfile