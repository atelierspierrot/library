<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\Object;

use ReflectionClass, ReflectionMethod, ReflectionProperty;
use Library\Helper\Code as CodeHelper;

/**
 * Magic handling of properties access
 *
 * ## Presentation
 *
 * This model constructs an accessible object in which you can dynamically set and get
 * properties on the fly without errors.
 *
 * The visibility of properties is kept for direct set or get.
 *
 * ## Rules
 *
 * All setter methods returns the object itself for chainability.
 *
 * To use static magic caller, your constructor must not require any argument.
 *
 * @author      Pierre Cassat & contributors <piero.wbmstr@gmail.com>
 */
abstract class AbstractInvokable implements InvokableInterface
{

    /**
     * Simple bit flag to check if the property acces was direct or through a `__call` call
     * @var $__isCalled bool
     */
     private $__isCalled = false;

    /**
     * Magic getter when calling an object as a function
     *
     * @see <http://www.php.net/manual/en/language.oop5.magic.php>
     * @see Library\Object\AbstractInvokable::_invokeGet()
     *
     * @param string $name The property name called on the object
     * @return misc This will return the result of the magic getter, or nothing if nothing can be done
     */
    public function __invoke($name)
    {
        $return = null;
        $property = CodeHelper::getPropertyName($name);
        if (!empty($property)) {
            $this->__isCalled = true;
            $return = call_user_func_array(array($this, '_invokeGet'), array($property));
            $this->__isCalled = false;
        }
        return $return;
    }

    /**
     * Magic handler when calling a non-existing method on an object 
     *
     * Magic method handling `getProp(default)`, `setProp(value)`, `unsetProp()`, `issetProp()` or `resetProp()`.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Library\Object\AbstractInvokable::_invokeIsset()
     * @see Library\Object\AbstractInvokable::_invokeReset()
     * @see Library\Object\AbstractInvokable::_invokeUnset()
     * @see Library\Object\AbstractInvokable::_invokeSet()
     * @see Library\Object\AbstractInvokable::_invokeGet()
     *
     * @param string $name The non-existing method name called on the object
     * @param array $arguments The arguments array passed calling the method
     * @return misc This will return the result of a magic method, or nothing if nothing can be done
     */
    public function __call($name, array $arguments)
    {
        $return = null;

        // unset, isset, reset
        if (in_array(substr($name, 0, 5), array('isset', 'reset', 'unset'))) {
            $property = CodeHelper::getPropertyName(substr($name, 5));
            switch(substr($name, 0, 5)) {
                case 'isset': $method = '_invokeIsset'; break;
                case 'reset': $method = '_invokeReset'; break;
                case 'unset': $method = '_invokeUnset'; break;
                default: break;
            }
        }
        
        // get, set
        if (in_array(substr($name, 0, 3), array('set', 'get'))) {
            $property = CodeHelper::getPropertyName(substr($name, 3));
            switch(substr($name, 0, 3)) {
                case 'get': $method = '_invokeGet'; break;
                case 'set': $method = '_invokeSet'; break;
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
     * Magic handler when calling a non-eixsting method statically on an object
     *
     * Magic static handling of `getProp(default)`, `setProp(value)`, `unsetProp()`, `issetProp()` or `resetProp()`.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     *
     * @param string $name The non-existing method name called on the object
     * @param array $arguments The arguments array passed calling the method
     * @return misc This will return the result of a magic method, or nothing if nothing can be done
     */
    public static function __callStatic($name, array $arguments)
    {
        $return = null;

        // unset, isset, reset
        if (in_array(substr($name, 0, 5), array('isset', 'reset', 'unset'))) {
            $property = CodeHelper::getPropertyName(substr($name, 5));
        }
        
        // get, set
        if (in_array(substr($name, 0, 3), array('set', 'get'))) {
            $property = CodeHelper::getPropertyName(substr($name, 3));
        }
        
        if (!empty($property) && self::__isInvokableStatic($property)) {
            $classname = get_called_class();
            try {
                $object = new $classname;
                $return = call_user_func_array(array($object, '__call'), array($name, $arguments));
            } catch(Exception $e) {}
        }
        return $return;
    }
    
    /**
     * Magic getter
     *
     * Magic method called when `$this->prop` is invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Library\Object\AbstractInvokable::_invokeGet()
     *
     * @param string $name The name of the property to get
     * @return misc This will return the result of a magic method, or nothing if nothing can be done
     */
    public function __get($name)
    {
        return self::_invokeGet($name);
    }
    
    /**
     * Magic setter
     *
     * Magic method called when `$this->arg = value` is invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Library\Object\AbstractInvokable::_invokeSet()
     *
     * @param string $name The name of the property to get
     * @param misc $value The value to set for the property
     * @return self Returns `$this` for method chaining
     */
    public function __set($name, $value)
    {
        return self::_invokeSet($name, $value);
    }
    
    /**
     * Magic checker
     *
     * Magic method called when `isset($this->prop)` or `empty($this->prop)` are invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Library\Object\AbstractInvokable::_invokeIsset()
     *
     * @param string $name The name of the property to get
     * @return bool This will return `true` if the property exists, `false` otherwise
     */
    public function __isset($name)
    {
        return self::_invokeIsset($name);
    }
    
    /**
     * Magic unsetter
     *
     * Magic method called when `unset($this->prop)` is invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @see Library\Object\AbstractInvokable::_invokeUnset()
     *
     * @param string $name The name of the property to get
     * @return self Returns `$this` for method chaining
     */
    public function __unset($name)
    {
        return self::_invokeUnset($name);
    }
    
    /**
     * Internal magic checker
     *
     * Magic method called when `issetProp()`, `isset($this->prop)` or `empty($this->prop)` are invoked.
     *
     * @param string $name The name of the property to get
     * @return bool This will return `true` if the property exists, `false` otherwise
     */
    protected function _invokeIsset($name)
    {
        if (!self::__isInvokable($name)) {
            return $this;
        }
        $is_static = self::__isStatic($name);
        $property = $this->findPropertyName($name);
        return !empty($property);
    }

    /**
     * Internal magic unsetter
     *
     * Magic method called when `unsetProp()` or `unset($this->prop)` are invoked.
     *
     * @param string $name The name of the property to get
     * @return self Returns `$this` for method chaining
     */
    protected function _invokeUnset($name)
    {
        if (!self::__isInvokable($name)) {
            return $this;
        }
        $is_static = self::__isStatic($name);
        $property = $this->findPropertyName($name);
        if (!empty($property)) {
            if ($is_static) {
                $this::${$name} = null;
            } else {
                $this->{$name} = null;
            }
        }
        return $this;
    }

    /**
     * Internal magic re-setter
     *
     * Magic method called when `resetProp(arg, value)` is invoked. As this can not work on
     * statics, in this case it is an alias of `unset`.
     *
     * @param string $name The name of the property to get
     * @return self Returns `$this` for method chaining
     */
    protected function _invokeReset($name)
    {
        if (!self::__isInvokable($name)) {
            return $this;
        }
        $is_static = self::__isStatic($name);
        $property = $this->findPropertyName($name);
        if (!empty($property)) {
            if ($is_static) {
                return $this->_invokeUnset($name);
            } else {
                $classname = get_class($this);
                $reflection = new ReflectionClass($classname);
                $properties = $reflection->getDefaultProperties();
                if (!empty($properties) && array_key_exists($property, $properties)) {
                    $this->{$property} = $properties[$property];
                }
            }
        }        
        return $this;
    }

    /**
     * Internal magic getter
     *
     * Magic method called when `getProp(arg, default)` or `$this->prop` are invoked.
     *
     * @param string $name The name of the property to get
     * @param misc $default The default value to return if the property doesn't exist
     * @return misc This will return the result of a magic method, or nothing if nothing can be done
     */
    protected function _invokeGet($name, $default = null)
    {
        if (!self::__isInvokable($name)) {
            return null;
        }
        $is_static = self::__isStatic($name);
        $property = $this->findPropertyName($name);
        if (!empty($property)) {
            return $is_static ? @$this::${$property} : @$this->{$property};
        }
        return $default;
    }

    /**
     * Internal magic setter
     *
     * Magic method called when `setProp(arg, value)` or `$this->arg = value` are invoked.
     *
     * @param string $name The name of the property to get
     * @param misc $value The value to set for the property
     * @return self Returns `$this` for method chaining
     */
    protected function _invokeSet($name, $value)
    {
        if (!self::__isInvokable($name)) {
            return $this;
        }
        $is_static = self::__isStatic($name);
        $property = $this->findPropertyName($name);
        if (!empty($property)) {
            if ($is_static) {
                $this::${$property} = $value;
            } else {
                $this->{$property} = $value;
            }
        }
        return $this;
    }

    /**
     * Check if a property is invokable in the final object
     *
     * Returns `false` if property access was direct and its scope is not public.
     * 
     * @param string $name The property name
     * @return bool
     */
    private function __isInvokable($name)
    {
        if (!$this->__isCalled) {
            $property = $this->findPropertyName($name);
            if (!empty($property)) {
                $reflection = new ReflectionProperty(get_class($this), $property);
                return $reflection->isPublic();
            }
        }
        return true;
    }

    /**
     * Check if a property is static in the final object
     *
     * @param string $name The property name
     * @return bool
     */
    private function __isStatic($name)
    {
        $property = $this->findPropertyName($name);
        if (!empty($property)) {
            $reflection = new ReflectionProperty(get_class($this), $property);
            return $reflection->isStatic();
        }
        return true;
    }

    /**
     * Check if a property is statically invokable in the final object
     *
     * Returns `false` if property access was direct and its scope is not static & public.
     * 
     * @param string $name The property name
     * @return bool
     */
    private static function __isInvokableStatic($name)
    {
        $classname = get_called_class();
        $reflection_class = new ReflectionClass($classname);
        $properties = $reflection_class->getStaticProperties();
        $property = self::findPropertyNameStatic($name, $classname);
        if (!empty($property)) {
            $reflection = new ReflectionProperty($classname, $property);
            return (bool) !($reflection->isPrivate());
        }
        return true;
    }

    /**
     * Search a property name in the current object with one or tow leading underscores
     *
     * @param string $name The property name to transform
     * @return string The transformed property name
     */
    public function findPropertyName($name)
    {
        return self::findPropertyNameStatic($name, $this);
    }

    /**
     * Search a property name in the current object with one or tow leading underscores
     *
     * @param string $name The property name to transform
     * @param string|object $object The object or a class name to work on
     * @return string The transformed property name
     */
    public static function findPropertyNameStatic($name, $object)
    {
        $property = null;
        if (property_exists($object, $name)) {
            $property = $name;
        } else {
            // _name
            $underscore_name = '_'.$name;
            if (property_exists($object, $underscore_name)) {
                $property = $underscore_name;
            } else {
                // __name
                $doubleunderscore_name = '__'.$name;
                if (property_exists($object, $doubleunderscore_name)) {
                    $property = $doubleunderscore_name;
                }
            }
        }
        return $property;
    }

}

// Endfile
