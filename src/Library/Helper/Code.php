<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\Helper;

use \Library\Helper\Text as TextHelper;

/**
 * Code helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Code as CodeHelper;
 *
 * @author      Piero Wbmstr <piero.wbmstr@gmail.com>
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
            return in_array($interface_name, $interfaces);
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
            return is_subclass_of($class_name, $mother_name);
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
            return ($object instanceof $class_name);
        }
        return false;
    }

	/**
	 * Launch a class's method fetching it arguments according to method declaration
	 *
	 * @param string $_class The class name
	 * @param string $_method The class method name
	 * @param misc $args A set of arguments to fetch
	 *
	 * @return misc
	 */
	public static function fetchArguments($_class = null, $_method = null, $args = null)
	{
		if (empty($_class) || empty($_method)) return;
		$args_def = array();
		if (!empty($args)) {
			$analyze = new \ReflectionMethod($_class, $_method);
			foreach ($analyze->getParameters() as $_param) {
				$arg_index = $_param->getName();
				$args_def[$_param->getPosition()] = isset($args[$arg_index]) ?
					$args[$arg_index] : ( $_param->isOptional() ? $_param->getDefaultValue() : null );
			}
		}
		return call_user_func_array(array($_class, $_method), $args_def);
	}

}

// Endfile
