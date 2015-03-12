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

namespace Library\Object;

use \InvalidArgumentException;
use \Patterns\Commons\Registry;
use \Library\Helper\Code as CodeHelper;

/**
 * Magic handling of properties access
 *
 * ## Presentation
 *
 * This model will store any property dynamically set in the `$_data` registry:
 *
 *     $obj->prop = value => $_data[prop] = value
 *     $obj->setProp(value) => $_data[prop] = value
 *
 * Depending on the flag of the object, to access the property, use:
 *
 *     echo $obj->prop // if flag=PUBLIC_PROPERTIES
 *     echo $obj->getProp() // if flag=PROTECTED_PROPERTIES
 *
 * Be very careful about the properties names as they will all be transformed in lower case
 * and underscore:
 *
 *     $obj->myPropertyName => $_data[ my_property_name ]
 *     $obj->myProperty_name => $_data[ my_property_name ]
 *
 *     $obj->setMyPropertyName => $_data[ my_property_name ]
 *     $obj->setMyProperty_name => $_data[ my_property_name ]
 *
 * ## Flags
 *
 * Three constants flags are defined in the class to let you choose the visibility of the object
 * properties:
 *
 * - `PUBLIC_PROPERTIES`: every type of property invocation is allowed,
 * - `PROTECTED_PROPERTIES`: only magic getter is allowed: `$obj->getProp()`, `$obj->prop` will return `null`,
 * - `UNAUTHORIZED_PROPERTIES`: only magic getter is allowed and an exception will be thrown using `$obj->prop`
 *
 * Default flag of the class is `PROTECTED_PROPERTIES` to force the magic getter usage and allow you
 * to override these methods in your child class if necessary, without worrying about method existence.
 *
 * ## Rules
 *
 * All setter methods returns the object itself for chainability.
 *
 * @author  piwi <me@e-piwi.fr>
 */
class RegistryInvokable
    implements InvokableInterface
{

    /**
     * Defines all registry properties as public
     */
     const PUBLIC_PROPERTIES = 1;

    /**
     * Defines all registry properties as protected
     */
     const PROTECTED_PROPERTIES = 2;

    /**
     * Defines all registry properties as protected and throw an error if an access is done
     */
     const UNAUTHORIZED_PROPERTIES = 4;

    /**
     * The registry
     * @var $_data \Patterns\Commons\Registry
     */
     protected $_data;

    /**
     * The object flag defining the properties visibility
     * @var $__flag int
     */
     private $__flag;

    /**
     * Simple bit flag to check if the property acces was direct or through a `__call` call
     * @var $__isCalled bool
     */
     private $__isCalled = false;

    /**
     * Object constructor
     *
     * @param array $data The object data to load in registry (optional)
     * @param int $flag The object flag (one of the class constants - default is `self::PROTECTED_PROPERTIES`)
     */
    public function __construct(array $data = null, $flag = self::PROTECTED_PROPERTIES)
    {
        $this->_data = new Registry;
        self::setFlag($flag);
        if (!empty($data)) {
            $this->setData($data);
        }
    }

    /**
     * Set the object flag for registry properties visibility
     *
     * @param int $flag Must be one of the class constants
     * @return self
     */
    public function setFlag($flag)
    {
        $this->__flag = $flag;
        return $this;
    }

    /**
     * Get the object flag for registry properties visibility
     *
     * @return int The object flag value
     */
    public function getFlag()
    {
        return $this->__flag;
    }

    /**
     * Magic handler when calling a non-existing method on an object 
     *
     * Magic method handling `getProp(default)`, `setProp(value)`, `unsetProp()`, `issetProp()` or `resetProp()`.
     *
     * Warning: the `reset` function here is the same as `unset` (not a real resetting to the original value).
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Patterns\Commons\Registry::getEntry()
     * @see Patterns\Commons\Registry::setEntry()
     * @see Patterns\Commons\Registry::isEntry()
     *
     * @param string $name The non-existing method name called on the object
     * @param array $arguments The arguments array passed calling the method
     * @return mixed This will return the result of a magic method, or nothing if nothing can be done
     */
    public function __call($name, array $arguments)
    {
        $return = null;
        
        // unset, isset, reset
        if (in_array(substr($name, 0, 5), array('isset', 'reset', 'unset'))) {
            $property = CodeHelper::getPropertyName(substr($name, 5));
            switch(substr($name, 0, 5)) {
                case 'isset': $method = '__isset'; break;
                case 'reset': $method = '__unset'; break;
                case 'unset': $method = '__unset'; break;
                default: break;
            }
        }
        
        // get, set
        if (in_array(substr($name, 0, 3), array('set', 'get'))) {
            $property = CodeHelper::getPropertyName(substr($name, 3));
            switch(substr($name, 0, 3)) {
                case 'get': $method = '__get'; break;
                case 'set': $method = '__set'; break;
                default: break;
            }
        }

        if (!empty($method)) {        
            array_unshift($arguments, $property);
            $this->__isCalled = true;
            $return = call_user_func_array(array($this, $method), $arguments);
            $this->__isCalled = false;
        }
        return $return;
    }
    
    /**
     * Avoiding magic static handler
     *
     * @return null
     */
    public static function __callStatic($name, array $arguments)
    {
        return null;
    }
    
    /**
     * Magic getter
     *
     * Magic method called when `getProp(arg, default)` or `$this->prop` are invoked. If the object
     * flag is set on `self::PROTECTED_PROPERTIES`, this will return null ; if the flag is set on
     * `self::UNAUTHORIZED_PROPERTIES`, an exception is thrown.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Patterns\Commons\Registry::getEntry()
     *
     * @param string $name The name of the property to get
     * @return mixed This will return the result of a magic method, or nothing if nothing can be done
     * @throws InvokableAccessException Throws an `InvokableAccessException` if direct access to properties is avoid by the
     *              `self::UNAUTHORIZED_PROPERTIES` flag
     */
    public function __get($name)
    {
        if (!$this->__isCalled && ($this->__flag & self::UNAUTHORIZED_PROPERTIES)) {
            throw new InvokableAccessException(CodeHelper::getPropertyName($name), get_class($this));
            return null;
        } elseif (!$this->__isCalled && ($this->__flag & self::PROTECTED_PROPERTIES)) {
            return null;
        }
        return $this->_data->getEntry(CodeHelper::getPropertyName($name));
    }
    
    /**
     * Magic setter
     *
     * Magic method called when `setProp(arg, value)` or `$this->arg = value` are invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Patterns\Commons\Registry::setEntry()
     *
     * @param string $name The name of the property to get
     * @param mixed $value The value to set for the property
     * @return self Returns `$this` for method chaining
     */
    public function __set($name, $value)
    {
        $this->_data->setEntry(CodeHelper::getPropertyName($name), $value);
        return $this;
    }
    
    /**
     * Magic checker
     *
     * Magic method called when `issetProp()`, `isset($this->prop)` or `empty($this->prop)` are invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Patterns\Commons\Registry::isEntry()
     *
     * @param string $name The name of the property to get
     * @return bool This will return `true` if the property exists, `false` otherwise
     */
    public function __isset($name)
    {
        return $this->_data->isEntry(CodeHelper::getPropertyName($name));
    }
    
    /**
     * Magic unsetter
     *
     * Magic method called when `unsetProp()` or `unset($this->prop)` are invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Patterns\Commons\Registry::_invokeUnset()
     *
     * @param string $name The name of the property to get
     * @return self Returns `$this` for method chaining
     */
    public function __unset($name)
    {
        $this->_data->setEntry(CodeHelper::getPropertyName($name), null);
        return $this;
    }

    /**
     * Global getter
     *
     * Get the value of a registry property or the whole registry array.
     *
     * @param string $name The name of a property to get if so (optional)
     * @param mixed $default A default value to send if the property doesn't exist in the object
     * @return mixed The value of the property if so, the default value if the property doesn't exist, or
     *              the flobal data array without a property name
     */    
    public function getData($name = null, $default = null)
    {
        if (!is_null($name)) {
            return $this->__get($name, false, $default);
        } else {
            return $this->_data->dump();
        }
    }
    
    /**
     * Global setter
     *
     * Set the value of a registry property or the whole registry array.
     *
     * @param string|array $value The name of the property if `$arg2` is defined, or an array of the whole object values
     * @param mixed $arg2 The value of a property to get if so (optional - `$value` must be a string)
     * @param mixed $default A default value to send if the property doesn't exist in the object
     * @return mixed The value of the property if so, the default value if the property doesn't exist, or
     *              the flobal data array without a property name
     * @throws \InvalidArgumentExcpetion Throws an InvalidArgumentExcpetion if `$name` is null and `$value` is not an array
     */    
    public function setData($value, $arg2 = null)
    {
        if (!is_null($arg2)) {
            return $this->__set($value, $arg2);
        } else {
            if (!is_array($value)) {
                throw new \InvalidArgumentException(
                    sprintf('First argument of method "%s()" must be an array to set the global object\'s data!', __METHOD__)
                );
            }
            foreach($value as $var=>$val) {
                $this->__set($var, $val);
            }
        }
        return $this;
    }
    
}

// Endfile
