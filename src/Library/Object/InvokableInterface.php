<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\Object;

/**
 * Magic handling of properties access interface
 *
 * @author      Pierre Cassat & contributors <piero.wbmstr@gmail.com>
 */
interface InvokableInterface
{

    /**
     * Magic handler when calling a non-existing method on an object 
     *
     * Magic method handling `getProp(default)`, `setProp(value)`, `unsetProp()`, `issetProp()` or `resetProp()` ;
     * it may dispatches to the corresponding defined magic method.
     *
     * @param string $name The non-existing method name called on the object
     * @param array $arguments The arguments array passed calling the method
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @return misc Must return the result of a magic method, or nothing if nothing can be done
     */
    public function __call($name, array $arguments);
    
    /**
     * Magic handler when calling a non-eixsting method statically on an object
     *
     * Magic static method handling `getProp(default)`, `setProp(value)`, `unsetProp()`, `issetProp()` or `resetProp()` ;
     * it may dispatches to the corresponding defined magic method.
     *
     * @param string $name The non-existing method name called on the object
     * @param array $arguments The arguments array passed calling the method
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @return misc Must return the result of a magic method, or nothing if nothing can be done
     */
    public static function __callStatic($name, array $arguments);
    
    /**
     * Magic getter
     *
     * Magic method called when `$this->prop` is invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @param string $name The name of the property to get
     * @return misc This will return the result of a magic method, or nothing if nothing can be done
     */
    public function __get($name);
    
    /**
     * Magic setter
     *
     * Magic method called when `$this->arg = value` is invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @param string $name The name of the property to get
     * @param misc $value The value to set for the property
     * @return self Returns `$this` for method chaining
     */
    public function __set($name, $value);
    
    /**
     * Magic checker
     *
     * Magic method called when `isset($this->prop)` or `empty($this->prop)` are invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @param string $name The name of the property to get
     * @return bool This will return `true` if the property exists, `false` otherwise
     */
    public function __isset($name);
    
    /**
     * Magic unsetter
     *
     * Magic method called when `unset($this->prop)` is invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @param string $name The name of the property to get
     * @return self Returns `$this` for method chaining
     */
    public function __unset($name);
    
}

// Endfile
