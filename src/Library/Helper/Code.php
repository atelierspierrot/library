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

namespace Library\Helper;

use \Library\Helper\Text as TextHelper;
use \Library\Helper\Directory as DirectoryHelper;

/**
 * Code helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Code as CodeHelper;
 *
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class Code
{

    /**
     * Transforms a property name from CamelCase to underscored
     *
     * @param string $name The property name to transform
     * @return string The transformed property name
     * @see Library\Helper\Text::fromCamelCase()
     */
    public static function getPropertyName($name)
    {
        return TextHelper::fromCamelCase( str_replace(' ', '_', $name) );
    }

    /**
     * Transform a property name from underscored to CamelCase used in magic method names
     *
     * @param string $name The property name to transform
     * @return string The transformed property name
     * @see Library\Helper\Text::toCamelCase()
     */
    public static function getPropertyMethodName($name)
    {
        return TextHelper::toCamelCase($name);
    }

    /**
     * Check if a class implements a certain interface
     *
     * @param string|object $class_name The class name to test or a full object of this class
     * @param string $interface_name The interface name to test
     * @return bool
     */
    public static function impelementsInterface($class_name, $interface_name)
    {
        if (is_object($class_name)) {
            $class_name = get_class($class_name);
        }
        if (class_exists($class_name)) {
            $interfaces = class_implements($class_name);
            return (bool) in_array($interface_name, $interfaces) || in_array(trim($interface_name, '\\'), $interfaces);
        }
        return false;
    }

    /**
     * Check if a class extends a certain class
     *
     * @param string|object $class_name The class name to test or a full object of this class
     * @param string $mother_name The class name to extend
     *
     * @return bool
     */
    public static function extendsClass($class_name, $mother_name)
    {
        if (is_object($class_name)) {
            $class_name = get_class($class_name);
        }
        if (class_exists($class_name)) {
            return (bool) is_subclass_of($class_name, $mother_name);
        }
        return false;
    }

    /**
     * Check if a an object is an instance of a class
     *
     * @param object $object
     * @param string $class_name
     *
     * @return bool
     */
    public static function isClassInstance($object, $class_name)
    {
        if (class_exists($class_name) && is_object($object)) {
            return (bool) ($object instanceof $class_name);
        }
        return false;
    }

    /**
     * @var string
     */
    const NAMESPACE_SEPARATOR                   = '\\';

    /**
     * @var string
     */
    const COMPOSER_AUTOLOADER_CLASSNAME         = '\Composer\Autoload\ClassLoader';

    /**
     * @var string
     */
    const COMPOSER_COMMON_NAMESPACES_AUTOLOADER = 'autoload_namespaces.php';

    /**
     * Test if a namespace can be found in declared classes or via Composer autoloader if so
     * 
     * This method will search concerned namespace in PHP declared classes namespaces and, if
     * found, in a Composer namespaces mapping usually stored in `vendor/composer/autoload_namespaces.php`,
     * searching for a directory that should contains the nameapace following the 
     * [FIG standards](https://github.com/php-fig/fig-standards).
     * 
     * @param string $namespace
     * 
     * @return bool
     */
    public static function namespaceExists($namespace)
    {
        $namespace = trim($namespace, self::NAMESPACE_SEPARATOR);
        $namespace .= self::NAMESPACE_SEPARATOR;

        foreach (get_declared_classes() as $name) {
            if (strpos($name, $namespace) === 0) {
                return true;
            }
        }

        if (class_exists($_composer_loader = self::COMPOSER_AUTOLOADER_CLASSNAME)) {
            $_composer_reflection = new \ReflectionClass($_composer_loader);
            $_loader_filename = $_composer_reflection->getFilename();
            $_classmap_filename = dirname($_loader_filename)
                .DIRECTORY_SEPARATOR
                .self::COMPOSER_COMMON_NAMESPACES_AUTOLOADER;
            if (file_exists($_classmap_filename)) {
                $namespaces_map = include $_classmap_filename;
                foreach ($namespaces_map as $_ns=>$_dir) {
                    $_ns = trim($_ns, self::NAMESPACE_SEPARATOR);
                    if (strpos($_ns, $namespace) === 0) {
                        return true;
                    }
                    if (substr($namespace, 0, strlen($_ns))===$_ns) {
                        if (false !== $pos = strrpos($namespace, self::NAMESPACE_SEPARATOR)) {
                            // namespaced class name
                            $namespace_path = strtr(substr($namespace, 0, $pos), self::NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR);
                            $namespace_name = substr($namespace, $pos + 1);
                        } else {
                            // PEAR-like class name
                            $namespace_path = null;
                            $namespace_name = $namespace;
                        }
                        $namespace_path .= strtr($namespace_name, '_', DIRECTORY_SEPARATOR);
                        
                        if (!is_array($_dir)) {
                            $_dir = array($_dir);
                        }
                        foreach ($_dir as $_testdir) {
                            $_d = DirectoryHelper::slashDirname($_testdir) . $namespace_path;
                            if (file_exists($_d) && is_dir($_d)) {
                                return true;
                            }
                        }

                    }
                }
            }
        }

        return false;
    }

    /**
     * Launch a function or class's method fetching it arguments according to its declaration
     *
     * @param   string  $method_name    The method name
     * @param   mixed   $arguments      A set of arguments to fetch
     * @param   string  $class_name     The class name
     * @param   array   $logs           Will be filled with indexes `miss` with missing required arguments
     *                                  and `rest` with unused `$arguments` - Passed by reference
     * @return  mixed
     * @throws  \InvalidArgumentException if the method is not callable
     */
    public static function fetchArguments($method_name = null, $arguments = null, $class_name = null, &$logs = array())
    {
        $args_def = self::organizeArguments($method_name, $arguments, $class_name, $logs);
        if (!empty($class_name)) {
            if (is_callable(array($class_name, $method_name))) {
                return call_user_func_array(array($class_name, $method_name), $args_def);
            } else {
                $_cls = is_object($class_name) ? get_class($class_name) : $class_name;
                throw new \InvalidArgumentException(
                    sprintf('Method "%s" of class object "%s" is not callable!', $method_name, $_cls)
                );
            }
        } else {
            return call_user_func_array($method_name, $args_def);
        }
    }

    /**
     * Organize an array of arguments to pass to a function or class's method according to its declaration
     *
     * Undefined arguments will be fetched with their default value if available or `null` otherwise.
     *
     * If `$arguments` is not an array, the method will search for the first argument with
     * no default value and define it on the `$arguments` value.
     *
     * @param   string  $method_name    The method name
     * @param   mixed   $arguments      A set of arguments to fetch
     * @param   string  $class_name     The class name
     * @param   array   $logs           Will be filled with indexes `miss` with missing required arguments
     *                                  and `rest` with unused `$arguments` - Passed by reference
     * @return  mixed
     */
    public static function organizeArguments($method_name = null, $arguments = null, $class_name = null, &$logs = array())
    {
        if (empty($method_name)) {
            return;
        }
        $args_passed = $arguments;
        $args_def = array();
        if (!empty($args_passed)) {
            if (!empty($class_name)) {
                $method_reflect = new \ReflectionMethod($class_name, $method_name);
            } else {
                $method_reflect = new \ReflectionFunction($method_name);
            }
            if (!is_array($args_passed)) {
                $tmp_index = -1;
                foreach ($method_reflect->getParameters() as $_param) {
                    $arg_pos = $_param->getPosition();
                    if (!$_param->isDefaultValueAvailable() && $tmp_index===-1) {
                        $tmp_index = $_param->getPosition();
                    } elseif (!$_param->isDefaultValueAvailable() && $tmp_index!==-1) {
                        if (!isset($logs['miss'])) {
                            $logs['miss'] = array();
                        }
                        $logs['miss'][$arg_pos] = sprintf('Argument "%s" is missing and defined on NULL', $_param->getName());
                    }
                }
                if ($tmp_index===-1) {
                    $tmp_index = 0;
                }
                $args_passed = array( $tmp_index=>$args_passed );
            }
            foreach ($method_reflect->getParameters() as $_param) {
                $arg_index = $_param->getName();
                $arg_pos = $_param->getPosition();
                $arg_val = null;
                if (isset($args_passed[$arg_index])) {
                     $arg_val = $args_passed[$arg_index];
                     unset($args_passed[$arg_index]);
                } elseif (isset($args_passed[$arg_pos])) {
                     $arg_val = $args_passed[$arg_pos];
                     unset($args_passed[$arg_pos]);
                } elseif ($_param->isDefaultValueAvailable()) {
                     $arg_val = $_param->getDefaultValue();
                } else {
                    if (!isset($logs['miss'])) {
                        $logs['miss'] = array();
                    }
                    $logs['miss'][$arg_pos] = sprintf('Argument "%s" is missing and defined on NULL', $arg_index);
                }
                $args_def[$arg_pos] = $arg_val;
            }
        }
        if (!empty($args_passed)) {
            $logs['rest'] = $args_passed;
        }
        return $args_def;
    }

}

// Endfile
