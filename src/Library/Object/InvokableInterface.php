<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
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

/**
 * Magic handling of properties access interface
 *
 * @author      Pierre Cassat & contributors <me@e-piwi.fr>
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
     * @return mixed Must return the result of a magic method, or nothing if nothing can be done
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
     * @return mixed Must return the result of a magic method, or nothing if nothing can be done
     */
    public static function __callStatic($name, array $arguments);
    
    /**
     * Magic getter
     *
     * Magic method called when `$this->prop` is invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @param string $name The name of the property to get
     * @return mixed This will return the result of a magic method, or nothing if nothing can be done
     */
    public function __get($name);
    
    /**
     * Magic setter
     *
     * Magic method called when `$this->arg = value` is invoked.
     *
     * @see <http://www.php.net/manual/en/language.oop5.overloading.php>
     * @param string $name The name of the property to get
     * @param mixed $value The value to set for the property
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

