<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\Helper;

use Library\Helper\Text as TextHelper;

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

}

// Endfile
