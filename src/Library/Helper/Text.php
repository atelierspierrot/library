<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
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
 * @author      Piero Wbmstr <me@e-piwi.fr>
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
     * Strip all special characters in a string
     *
     * @param string $string The string to format
     * @return string
     */
    public static function stripSpecialChars($string = '')
    {
        $search = explode(',',
            "À,Á,Â,Ã,Ä,Å,à,á,â,ã,ä,å,Æ,Ç,æ,ç,Ñ,ñ,Ĵ,ĵ,Œ,œ,È,É,Ê,Ë,è,é,ê,ë,Ì,Í,Î,Ï,ì,í,î,ï,Ò,Ó,Ô,Õ,Ö,Ø,ò,ó,ô,õ,ö,ø,Ù,Ú,Û,Ü,ù,ú,û,ü,ů,Ů,ũ,Ũ,Ý,ý,ÿ,Ÿ");
        $replace = explode(',', 
            "A,A,A,A,A,A,a,a,a,a,a,a,AE,C,ae,c,N,n,J,j,OE,oe,E,E,E,E,e,e,e,e,I,I,I,I,i,i,i,i,O,O,O,O,O,O,o,o,o,o,o,o,U,U,U,U,u,u,u,u,u,U,u,U,Y,y,y,Y");
        return str_replace($search, $replace, $string);
/*
        $search = array(
            '@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i','@[^a-zA-Z0-9]@'
        );
        $replace = array('e','a','i','u','o','c',' ');
        $string =  preg_replace($search, $replace, $string);
        return $string;
*/
    }

    /**
     * Get a slugified string
     *
     * By Miguel Santirso (http://sourcecookbook.com/en/recipes/8/function-to-slugify-strings-in-php)
     *
     * @param string $string The string to format
     * @return string
     */
    public static function slugify($string = '')
    {
        $string = preg_replace('~[^\\pL\d]+~u', '-', $string); 
        if (function_exists('iconv')) {
            $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
        } 
        $string = preg_replace('~[^-\w]+~', '', strtolower(trim($string, '-')));
        return $string;
    }

    /**
     * Transform a string to a human readable one
     *
     * @param string $string The string to transform
     * @return string The transformed version of `$string`
     */
    public static function getHumanReadable($string = '')
    {
        return trim(str_replace(array('_', '.', '/'), ' ', $string));
    }

    /**
     * Transform a name in CamelCase
     *
     * @param string $name The string to transform
     * @param string $replace Replacement character
     * @param bool $capitalize_first_char May the first letter be in upper case (default is `true`)
     * @return string The CamelCase version of `$name`
     */
    public static function toCamelCase($name = '', $replace = '_', $capitalize_first_char = true)
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
    public static function fromCamelCase($name = '', $replace = '_', $lowerize_first_char = true)
    {
        if ($lowerize_first_char) {
            $name[0] = strtolower($name[0]);
        }
        $func = create_function('$c', 'return "'.$replace.'" . strtolower($c[1]);');
        return trim(preg_replace_callback('/([A-Z])/', $func, $name), $replace);
    }

}

// Endfile
