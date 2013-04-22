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
 * HTML language helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Html as HtmlHelper;
 *
 * @author      Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Html
{

// -------------------------
// DOM construction
// -------------------------

    /**
     * The global DOM IDs register
     */
    static $dom_id_register = array();

    /**
     * Verify if a reference is already defined in the DOM IDs register
     *
     * @param string $reference The reference to search
     * @return bool True if the reference exists in the register, false otherwise
     */
    public static function hasId($reference)
    {
        return isset(self::$dom_id_register[$reference]);
    }

    /**
     * Get a DOM unique ID 
     *
     * @param string $reference A reference used to store the ID (and retrieve it - by default, a uniqid)
     * @param string|bool $base_id A string that will be used to construct the ID, if set to `true`, the reference will be used as `$base_id`)
     * @return str The unique ID created or the existing one for the reference if so
     */
    public static function getId($reference = null, $base_id = null)
    {
        if (empty($reference)) {
            $reference = uniqid();
        }
        if (isset(self::$dom_id_register[$reference])) {
            return self::$dom_id_register[$reference];
        }
        return self::getNewId($reference, $base_id);
    }

    /**
     * Create and get a new DOM unique ID 
     *
     * @param string $reference A reference used to store the ID (and retrieve it - by default, a uniqid)
     * @param string|bool $base_id A string that will be used to construct the ID, if set to `true`, the reference will be used as `$base_id`)
     * @return str The unique ID created
     */
    public static function getNewId($reference = null, $base_id = null)
    {
        if (empty($reference)) {
            $reference = uniqid();
        }
        if (true===$base_id) {
            $base_id = $reference;
        }
        if (!is_null($base_id)) {
            $new_id = $base_id;
            while(in_array($new_id, self::$dom_id_register)) {
                $new_id = $base_id.'_'.uniqid();
            }
        } else {
            $new_id = uniqid();
            while(in_array($new_id, self::$dom_id_register)) {
                $new_id = uniqid();
            }
        }
        self::$dom_id_register[$reference] = $new_id;
        return $new_id;
    }

// -------------------------
// HTML formatting
// -------------------------

    static $html_tag_closure = ' />';

    /**
     * Set the HTML tags closure (` />` by default)
     *
     * @param string $closure The tag closure string
     */
    public static function setHtmlTagClosure($closure)
    {
        self::$html_tag_closure = $closure;
    }
    
    /**
     * Build an HTML string for a specific tag with attributes
     *
     * @param string $tag_name The tag name
     * @param string $content The tag content
     * @param array $attrs An attributes array
     * @param bool $intag_close Can this kind of tag be directly closed (default is `false`)
     * @return str The HTML string for the tag
     */
    public static function writeHtmlTag($tag_name, $content = '', $attrs = array(), $intag_close = false)
    {
        $str = '<'.$tag_name.self::parseAttributes( $attrs );
        if (empty($content) && true===$intag_close) {
            $str .= self::$html_tag_closure;
        } else {
            $str .= '>'.(is_string($content) ? $content : '').'</'.$tag_name.'>';
        }
        return $str;
    }

    /**
     * Build an attributes HTML string from an array like `variable => value` pairs
     *
     * @param array $attrs The attributes array
     * @return str The attributes string ready for HTML insertion
     */
    public static function parseAttributes(array $attrs = array())
    {
        $str = '';
        foreach($attrs as $var=>$val) {
            $str .= ' '.$var.'="'.(is_array($val) ? join(' ', $val) : $val).'"';
        }
        return $str;
    }

    /**
     * Build an HTML string to use in javascripts attributes or functions
     *
     * @param string $str The HTML string to protect
     * @param bool $protect_quotes Protect all quotes (simple and double) with a slash
     * @return str The HTML string ready for javascript insertion
     */
    public static function javascriptProtect($str = '', $protect_quotes = false)
    {
        $str = preg_replace('/\s\s+/', ' ', $str);
        $str = htmlentities($str);
        if (true===$protect_quotes) {
            $str = str_replace("'", "\'", $str);
            $str = str_replace('"', '\"', $str);
        }
        return $str;
    }

}

// Endfile
