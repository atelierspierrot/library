<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\Helper;

/**
 * Text helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Text as TextHelper;
 *
 * @author      Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Text
{

    /**
     * Truncate a string at a maximum length, adding it a suffix like '...'
     *
     * @param string $string The string to cut
     * @param integer $length The maximum length to keep (`120` by default)
     * @param string $end_str The suffix to add if the string was cut (` ...` by default)
     * @return string
     */
    public static function cut($string = '', $length = 120, $end_str = ' ...')
    {
        if (empty($string)) return '';
        if (strlen($string) >= $length) {
            $stringint = substr($string, 0, $length);
            $last_space = strrpos($stringint, " ");
            $stringinter = substr($stringint, 0, $last_space).$end_str;
            if (strlen($stringinter) === strlen($end_str)) {
                $stringcut = $stringint.$end_str;
            } else {
                $stringcut = $stringinter;
            }
        } else {
            $stringcut = $string;
        }
        return $stringcut;
    }

    /**
     * Transform a name in CamelCase
     *
     * @param string $name The string to transform
     * @param string $replace Replacement character
     * @param bool $capitalize_first_char May the first letter be in upper case (default is `true`)
     * @return string The CamelCase version of `$name`
     */
    public static function toCamelCase($name, $replace = '_', $capitalize_first_char = true)
    {
        if ($capitalize_first_char) {
            $name[0] = strtoupper($name[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return trim(preg_replace_callback('#'.$replace.'([a-z])#', $func, $name), $replace);
    }

    /**
     * Transform a name from CamelCase to other
     *
     * @param string $name The string to transform
     * @param string $replace Replacement character
     * @param bool $capitalize_first_char May the first letter be in upper case (default is `true`)
     * @return string The CamelCase version of `$name`
     */
    public static function fromCamelCase($name, $replace = '_', $lowerize_first_char = true)
    {
        if ($lowerize_first_char) {
            $name[0] = strtolower($name[0]);
        }
        $func = create_function('$c', 'return "'.$replace.'" . strtolower($c[1]);');
        return trim(preg_replace_callback('/([A-Z])/', $func, $name), $replace);
    }

}

// Endfile
