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
 * URL common methods
 *
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Url
{

    /**
     * Get the current browser URL
     *
     * @param bool $entities Protect '&' entities parsing them in '&amp;' ? (default is FALSE)
     * @param bool $base Do you want just the base URL, without any URI (default is FALSE)
     * @param bool $no_file Do you want just the base URL path, without the input file and any URI (default is FALSE)
     * @return string The URL found
     */
    public static function getCurrentUrl($entities = false, $base = false, $no_file = false)
    {
        $protocl = self::getHttpProtocol();
        if (!isset($GLOBALS['REQUEST_URI'])) {
            if (isset($_SERVER['REQUEST_URI'])) {
                $GLOBALS['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
            } else {
                $GLOBALS['REQUEST_URI'] = $_SERVER['PHP_SELF'];
                if (
                    $_SERVER['QUERY_STRING'] && !strpos($_SERVER['REQUEST_URI'], '?')
                ) {
                    $GLOBALS['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
                }
            }
        }
        $url = $protocl.'://'.$_SERVER['HTTP_HOST'].$GLOBALS['REQUEST_URI'];
        if ($base && strpos($url, '?')) {
            $url = substr($url, 0, strrpos($url, '?'));
        }
        if ($no_file && strpos($url, '/')) {
            $url = substr($url, 0, strrpos($url, '/')).'/';
        }
        if (true===$entities) {
            $url = str_replace('&', '&amp;', $url);
        }
        return $url;
    }
    
    /**
     * @ignore
     */
    public static function getHttpProtocol()
    {
        return ((
            (isset($_SERVER["SCRIPT_URI"]) && substr($_SERVER["SCRIPT_URI"],0,5) == 'https')
            || isset($_SERVER['HTTPS'])
        ) ? 'https' : 'http');
    }
    
    /**
     * Parse an URL and returns its composition as an array, with the URI query if so
     *
     * @param string $url The URL to parse (required)
     * @return array An array of the URL components
     */
    public static function urlParser($url)
    {
        if (!strlen($url)) return;
        $_urls = array_merge( @parse_url($url), array('params'=>array()) );
        if (isset($_urls['query'])) {
            parse_str($_urls['query'], $_urls['params']);
        }
        return $_urls;	
    }
    
    /**
     * Validate an URL
     *
     * @param string $url The string to validate
     * @param bollean/string $localhost Is it locally (useful for validating 'http://localhost ...') (FALSE by default) - You can specify a string to check
     * @param array $protocols Table of Internet protocols to verify (by default : 'http', 'https', 'ftp')
     * @return bool Returns `true` if this is a URL in one of the specified protocols
     */
    public static function isUrl($url = null, $protocols = array('http','https','ftp'), $localhost = false)
    { 
        if (is_null($url) || !$url || !is_string($url)) return;
        if ($localhost) {
            if (!is_string($localhost)) $localhost = 'localhost';
            if (substr_count($url, $localhost)) return true;
        }
		return (bool) preg_match("/^[".join('|', $protocols)."]+[:\/\/]+[A-Za-z0-9\-_]+(\\.)?+[A-Za-z0-9\.\/%&=\?\-_]+$/i", $url); 
    }
    
    /**
     * Validate an email adress
     *
     * @param string $email The string to validate
     * @return bool Returns `true` if this is an email
     */
    public static function isEmail($email = null)
    {
        if (is_null($email) || !$email || !is_string($email)) return;
		return (bool) preg_match('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email);
    }

}

// Endfile