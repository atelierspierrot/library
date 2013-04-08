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
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Url as UrlHelper;
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
    public static function getRequestUrl($entities = false, $base = false, $no_file = false)
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
     * Get the current 'http' or 'https' protocol
     *
     * @return string The current protocol
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
    public static function parse($url)
    {
        if (!strlen($url)) return;
        $_urls = array_merge( @parse_url($url), array('params'=>array()) );
        if (isset($_urls['query'])) {
            parse_str($_urls['query'], $_urls['params']);
        }
        return $_urls;	
    }
    
    /**
     * Returns the URL with paths cleaned (`./` and `../` are resolved)
     *
     * Inspired by SPIP <http://spip.net>
     *
     * @param string $url The URL to resolve
     * @param boolean $realpath Returns the real path of the path (default is `false`)
     * @return string The resolved path, or real path if so
     */
    public static function resolvePath($url, $realpath = false)
    {
        while (
            preg_match(',/\.?/,', $url, $regs)		# supprime // et /./
            || preg_match(',/[^/]*/\.\./,S', $url, $regs)	# supprime /toto/../
            || preg_match(',^/\.\./,S', $url, $regs)		# supprime les /../ du haut
        ) {
            $url = str_replace($regs[0], '/', $url);
        }
        $url = '/'.preg_replace(',^/,S', '', $url);
        if ($realpath && $ok = @realpath($url) )
            return $ok;
        return $url;		
    }

    /**
     * Returns an URL with leading 'http://' if it was absent
     * 
     * Inspired by SPIP <http://spip.net>
     *
     * @param string $url The URL to resolve
     * @return string The resolved URL
     */
    public static function resolveHttp($url)
    {
        $url = preg_replace(',^feed://,i', 'http://', $url);
        if (!preg_match(',^[a-z]+://,i', $url)) $url = 'http://'.$url;
        return $url;
    }

    /**
     * Returns if possible an absolute URL in the current system
     *
     * @param string $url The URL to resolve
     * @return string The resolved URL
     */
    public static function getAbsoluteUrl($url)
    {
        // Si c'est déjà une URL absolue, on renvoi
        if (self::isUrl($url)) return $url;
        $url = self::resolvePath($url);
        $current_url = self::getRequestUrl(true, true);
        $curr = substr($current_url, 0, strrpos($current_url, '/') );
        return $curr.$url;
    }

    /**
     * Rebuilds a complete URL based on its elements as an array
     * 
     * @param array $_urls An array of URLs elements : `scheme`, `user`, `pass`, `host`, `port`,
     *                  `path`, `params`, `hash`
     * @param string/array $not_toput An element or an array of elements to NOT include
     * @return string The rebuilt URL
     */
    public static function build(array $_urls = array(), $not_toput = false)
    {
        if (0===count($_urls)) return;
        $_ntp = $not_toput ? ( is_array($not_toput) ? $not_toput : array($not_toput) ) : array();
        if (isset($_urls['params']))
            $_urls['params'] = array_filter($_urls['params']);
        $n_url = 
            ( (isset($_urls['scheme']) && !in_array('scheme', $_ntp)) ? $_urls['scheme'].'://' : 'http://')
            .( (isset($_urls['user']) && !in_array('user', $_ntp)) ? $_urls['user'] : '')
            .( (isset($_urls['pass']) && !in_array('pass', $_ntp)) ? ':'.$_urls['pass'] : '')
            .( ((isset($_urls['user']) && !in_array('user', $_ntp)) || (isset($_urls['pass']) && !in_array('pass', $_ntp))) ? '@' : '')
            .( (isset($_urls['host']) && !in_array('host', $_ntp)) ? $_urls['host'] : '')
            .( (isset($_urls['port']) && !in_array('port', $_ntp)) ? ':'.$_urls['port'] : '')
            .( (isset($_urls['path']) && !in_array('path', $_ntp)) ? $_urls['path'] : '')
            .( (isset($_urls['params']) && !in_array('params', $_ntp)) ? '?'.http_build_query($_urls['params']) : '')
            .( (isset($_urls['hash']) && !in_array('hash', $_ntp)) ? '#'.$_urls['hash'] : '');
        return trim($n_url, '?&');
    }

    /**
     * Get an URL parameter by its name
     *
     * @param string $url The URL to parse ({@link self::getRequestUrl()} by default)
     * @param string $param The parameter name to get ; if `false` (by default), returns the array of parameters
     * @return string/array The retrieved value for the given parameter or the array of parameters
     */
    public static function getParam($url = false, $param = false)
    {
        if (!$url || !strlen($url))
            $url = self::getRequestUrl();
        $parsed_url = self::parse($url);
        $params = (isset($parsed_url['params']) && count($parsed_url['params'])) 
            ? $parsed_url['params'] : false;
        if ($param && strlen($param)) {
            if ($params) {
                foreach($params as $p=>$v) {
                    if($p==$param) return $v;
                }
            }
            return false;
        }
        return $params;
    }

    /**
     * Set or overwrite an URL parameter
     *
     * @param string $url The URL to parse ({@link self::getRequestUrl()} by default)
     * @param string $var The name of the parameter to set
     * @param string $val The value to set for the parameter `$var`
     * @param bool $rebuild Returns a `self::build()` URL or not (default is `true`)
     * @return string/array The full URL or the array of complete URLs elements
     */
    public static function setParam($url = false, $var = '', $val = false, $rebuild = true)
    {
        $url_entree = $url;
        if (!$url || !is_array($url)) {
            $_url = $url ? $url : self::getRequestUrl();
            $url = self::parse($_url);
        }
        $url['params'][$var] = $val;
        if ($rebuild) return self::build($url);
        return $url;
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
        if (is_null($url) || !$url || !is_string($url)) return false;
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
        if (is_null($email) || !$email || !is_string($email)) return false;
		return (bool) preg_match('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email);
    }

}

// Endfile