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

namespace Library\ServiceContainer;

/**
 * A simple service container interface
 *
 * The "register" system will store a callback in the registry
 * with a specific name to use when trying to get a service.
 * The getter will throw an error by default if the service
 * does not exist, except when using the `FAIL_GRACEFULLY` constant.
 * Any service is overwritable by default but can be not using
 * the `$protected = true` register argument.
 *
 */
interface ServiceContainerInterface
{

    /**
     * Use this constant to NOT throw error when trying to get an unknown service
     */
    const FAIL_GRACEFULLY = 1;

    /**
     * Use this constant to throw error when trying to get an unknown service
     *
     * This is the default behavior.
     */
    const FAIL_WITH_ERROR = 2;

    /**
     * Define a service constructor like `array( name , callback , protected )` or a closure
     *
     * @param   string      $name
     * @param   array       $provider A service array constructor like `array( name , callback , protected )`
     *          callable    $provider A callback as a closure that must return the service object: function ($name, $arguments) {}
     *          ServiceProviderInterface    $provider A `\Library\ServiceContainer\ServiceProviderInterface` instance
     * @return mixed
     */
    public function setProvider($name, $provider);

    /**
     * Register a new service called `$name` declared as NOT protected by default
     *
     * @param   string          $name
     * @param   object|callable $callback
     * @param   bool            $protected
     * @return  mixed
     */
    public function setService($name, $callback, $protected = false);

    /**
     * Get a service called `$name` throwing an error by default if it does not exist yet and can not be created
     *
     * @param   string  $name
     * @param   array   $arguments
     * @param   int     $failure
     * @return  mixed
     */
    public function getService($name, array $arguments = array(), $failure = self::FAIL_WITH_ERROR);

    /**
     * Test if a service exists in the container
     *
     * @param   string  $name
     * @return  mixed
     */
    public function hasService($name);

    /**
     * Unset a service if it is not protected
     *
     * @param   string  $name
     * @return  mixed
     */
    public function unsetService($name);

}

// Endfile