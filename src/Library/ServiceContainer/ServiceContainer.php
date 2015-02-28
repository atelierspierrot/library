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

use \ErrorException;
use \Patterns\Commons\Collection;
use \Patterns\Traits\SingletonTrait;
use \Library\Helper\Code as CodeHelper;

/**
 * A simple service container with constructors
 *
 * @requires PHP 5.4+
 */
class ServiceContainer
    implements ServiceContainerInterface
{

    /**
     * This class inherits from \Patterns\Traits\SingletonTrait
     */
    use SingletonTrait;

    /**
     * @var \Patterns\Commons\Collection
     */
    private $_services;

    /**
     * @var \Patterns\Commons\Collection
     */
    private $_services_providers;

    /**
     * @var \Patterns\Commons\Collection
     */
    private $_services_protected;

    /**
     * Private constructor
     */
    private function __construct(){}

    /**
     * Initialize the service container system
     *
     * @param   array $initial_services
     * @param   array $services_providers
     * @param   array $services_protected
     * @return  $this
     */
    public function init(
        array $initial_services     = array(),
        array $services_providers   = array(),
        array $services_protected   = array()
    ) {
        // object can only be initialized once
        if (!is_object($this->_services)) {
            $this->_services                = new Collection();
            $this->_services_protected      = new Collection();
            $this->_services_providers      = new Collection();
            if (!empty($initial_services)) {
                foreach ($initial_services as $_name=>$_service) {
                    $this->setService($_name, $_service);
                }
            }
            if (!empty($services_providers)) {
                foreach ($services_providers as $_name=>$_provider) {
                    $this->setProvider($_name, $_provider);
                }
            }
            if (!empty($services_protected)) {
                foreach ($services_protected as $_name) {
                    $this->setProtected($_name);
                }
            }
        }
        return $this;
    }

    /**
     * Define a service constructor like `array( name , callback , protected )` or a closure
     *
     * @param   string      $name
     * @param   array       $provider A service array constructor like `array( name , callback , protected )`
     *          callable    $provider A callback as a closure that must return the service object: function ($name, $arguments) {}
     *          ServiceProviderInterface    $provider A `\Library\ServiceContainer\ServiceProviderInterface` instance
     * @return  $this
     */
    public function setProvider($name, $provider)
    {
        if (is_object($provider) && CodeHelper::implementsInterface($provider, 'Library\ServiceContainer\ServiceProviderInterface')) {
            $provider->register($this);
        }
        $this->_services_providers->offsetSet($name, $provider);
        return $this;
    }

    /**
     * Get a service constructor if it exists
     *
     * @param   string  $name
     * @return  mixed
     */
    public function getProvider($name)
    {
        return $this->hasProvider($name) ?
            $this->_services_providers->offsetGet($name) : null;
    }

    /**
     * Test if a constructor exists for a service
     *
     * @param   string  $name
     * @return  bool
     */
    public function hasProvider($name)
    {
        return (bool) $this->_services_providers->offsetExists($name);
    }

    /**
     * Define a service as protected
     *
     * @param   string  $name
     * @return  $this
     */
    public function setProtected($name)
    {
        $this->_services_protected->offsetSet($name, true);
        return $this;
    }

    /**
     * Test if a service is protected
     *
     * @param   string  $name
     * @return  bool
     */
    public function isProtected($name)
    {
        return (bool) (
            $this->_services_protected->offsetExists($name) &&
            $this->_services_protected->offsetGet($name)===true
        );
    }

    /**
     * Register a new service called `$name` declared as NOT protected by default
     *
     * @param   string          $name
     * @param   object|callable $callback
     * @param   bool            $protected
     * @return  $this
     * @throws  \ErrorException
     */
    public function setService($name, $callback, $protected = false)
    {
        if ($this->hasService($name) && $this->isProtected($name)) {
            throw new ErrorException(
                sprintf('Over-write service "%s" is forbidden!', $name)
            );
        }
        if ($this->_validateService($name, $callback)) {
            $this->_services->setEntry($name, $callback);
            if ($protected) {
                $this->setProtected($name);
            }
        }
        return $this;
    }

    /**
     * Get a service called `$name` throwing an error by default if it does not exist yet and can not be created
     *
     * @param   string  $name
     * @param   array   $arguments
     * @param   int     $failure
     * @return  mixed
     * @throws  \ErrorException
     */
    public function getService($name, array $arguments = array(), $failure = self::FAIL_WITH_ERROR)
    {
        if ($this->hasService($name)) {
            return $this->_services->offsetGet($name);
        } elseif ($this->hasProvider($name)) {
            $this->_constructService($name, $arguments);
            if ($this->hasService($name)) {
                return $this->_services->offsetGet($name);
            }
        }
        if ($failure & self::FAIL_WITH_ERROR) {
            throw new ErrorException(
                sprintf('Service "%s" not known or cannot be created!', $name)
            );
        }
        return null;
    }

    /**
     * Test if a service exists in the container
     *
     * @param   string  $name
     * @return  mixed
     */
    public function hasService($name)
    {
        return (bool) $this->_services->offsetExists($name);
    }

    /**
     * Unset a service if it is not protected
     *
     * @param   string  $name
     * @return  mixed
     * @throws  \ErrorException
     */
    public function unsetService($name)
    {
        if ($this->hasService($name)) {
            if (!$this->isProtected($name)) {
                if ($this->hasProvider($name)) {
                    $data = $this->getProvider($name);
                    if (is_object($data) && CodeHelper::implementsInterface($data, 'Library\ServiceContainer\ServiceProviderInterface')) {
                        $data->terminate($this);
                    }
                }
                $this->_services->offsetUnset($name);
            } else {
                throw new ErrorException(
                    sprintf('Cannot unset protected service "%s"!', $name)
                );
            }
        }
        return $this;
    }

    /**
     * Validate a service
     *
     * This allows you to implement this method in inherited class.
     *
     * @param   string  $name
     * @param   object  $object
     * @return  bool
     */
    protected function _validateService($name, $object)
    {
        return true;
    }

    /**
     * Construct a service based on its `constructor` item and references it
     *
     * @param   string  $name
     * @param   array   $arguments
     * @return  void
     * @throws  ErrorException
     */
    protected function _constructService($name, array $arguments = array())
    {
        if ($this->hasProvider($name)) {
            $data = $this->getProvider($name);
            if (is_object($data) && CodeHelper::implementsInterface($data, 'Library\ServiceContainer\ServiceProviderInterface')) {
                $data->boot($this);
                $this->setService($name, $data);
            } elseif (is_callable($data) || ($data instanceof \Closure)) {
                try {
                    $item = call_user_func_array(
                        $data, array($this, $name, $arguments)
                    );
                    $this->setService($name, $item);
                } catch (\Exception $e) {
                    throw new ErrorException(
                        sprintf('An error occurred while trying to create a "%s" service!', $name),
                        0, 1, __FILE__, __LINE__, $e
                    );
                }
            } elseif (is_array($data)) {
                $this->setService(
                    $name,
                    $data[1],
                    isset($data[2]) ? $data[2] : false
                );
            } else {
                throw new ErrorException(
                    sprintf('A "%s" service constructor must be a valid callback!', $name)
                );
            }
        }
    }

}

// Endfile