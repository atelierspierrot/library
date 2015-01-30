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

/**
 * URL common methods
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Url as UrlHelper;
 *
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class Url
{

    /**
     * Get the current browser/server URL
     *
     * @param bool $entities Protect '&' entities parsing them in '&amp;' ? (default is FALSE)
     * @param bool $base Do you want just the base URL, without any URI (default is FALSE)
     * @param bool $no_file Do you want just the base URL path, without the input file and any URI (default is FALSE)
     * @param bool $no_rewrite Do you want the real file pointed by the URL in case of URL rewriting (default is FALSE)
     * 
     * @return string
     */
    public static function getRequestUrl($entities = false, $base = false, $no_file = false, $no_rewrite = false)
    {
        $protocol = self::getHttpProtocol();
        if ($no_rewrite) {
            $url = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
            if (!empty($_SERVER['QUERY_STRING'])) {
                $url .= '?'.$_SERVER['QUERY_STRING'];
            }
        } else {
            $request_uri = isset($GLOBALS['REQUEST_URI']) ? $GLOBALS['REQUEST_URI'] : null;
            if (empty($request_uri)) {
                if (isset($_SERVER['REQUEST_URI'])) {
                    $request_uri = $_SERVER['REQUEST_URI'];
                } else {
                    $request_uri = $_SERVER['PHP_SELF'];
                    if (
                        $_SERVER['QUERY_STRING'] && !strpos($_SERVER['REQUEST_URI'], '?')
                    ) {
                        $request_uri .= '?'.$_SERVER['QUERY_STRING'];
                    }
                }
            }
            $url = $protocol.'://'.$_SERVER['HTTP_HOST'].$request_uri;
        }
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
        if (!strlen($url)) {
            return;
        }
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
            preg_match(',/\.?/,', $url, $regs)      # supprime // et /./
            || preg_match(',/[^/]*/\.\./,S', $url, $regs)   # supprime /toto/../
            || preg_match(',^/\.\./,S', $url, $regs)        # supprime les /../ du haut
        ) {
            $url = str_replace($regs[0], '/', $url);
        }
        $url = '/'.preg_replace(',^/,S', '', $url);
        if ($realpath && $ok = @realpath($url) ) {
            return $ok;
        }
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
        if (!preg_match(',^[a-z]+://,i', $url)) {
            $url = 'http://'.$url;
        }
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
        if (self::isUrl($url)) {
            return $url;
        }
        $url            = self::resolvePath($url);
        $current_url    = self::getRequestUrl(true, true);
        $curr           = substr($current_url, 0, strrpos($current_url, '/') );
        return $curr.$url;
    }

    /**
     * Global URL builder
     *
     * @param string/array/null $param A parameter to set, or an array like `param => value` to set in URL
     * @param string/null $value The value of the `$param` argument (if it is a string)
     * @param string/null $url The URL to work on (`self::getRequestUrl()` by default)
     * @return string The final rebuilt URL
     */
    public static function url($param = null, $value = null, $url = null)
    {
        if (is_null($url) OR !strlen($url)) 
            $url = self::getRequestUrl();

        if (!is_null($param)) {
            if (is_array($param) && is_null($value)) {
                foreach($param as $param_p=>$value_p) {
                    $url = self::setParameter($param_p, $value_p, $url);
                }
            } elseif (is_null($value)) {
                $parsed_url = self::parse($url);
                if (isset($parsed_url['params']) && isset($parsed_url['params'][$param])) {
                    return $parsed_url['params'][$param];
                }
                return false;
            } elseif (!is_null($value)) {
                $url = self::setParameter($param, $value, $url);
            }
        }

        return self::build(self::parse($url));
    }

    /**
     * Get the value of an URL parameter
     *
     * @param   string/bool     $param  A parameter to get, or `false` to get the global parameters array
     * @param   string/null     $url    The URL to work on (`self::getRequestUrl()` by default)
     * @return  string/array    The parameter value or the global array of parameters
     */
    public static function getParameter($param = false, $url = false)
    {
        if (!$url || !strlen($url)) {
            $url = self::getRequestUrl();
        }

        $parsed_url = self::parse($url);
        $params = (isset($parsed_url['params']) && count($parsed_url['params'])) 
            ? $parsed_url['params'] : false;

        if ($param && strlen($param)) {
            if ($params) {
                foreach($params as $p=>$v) {
                    if ($p==$param) return $v;
                }
            }
            return false;
        }
        return $params;
    }

    /**
     * Set the value of an URL parameter
     *
     * @param   string/bool     $var        A parameter to get, or `false` to get the global parameters array
     * @param   string/false    $val        The value of the `$param` argument (if `null`, the argument is stripped)
     * @param   string/false    $url        The URL to work on (`self::getRequestUrl()` by default)
     * @param   bool            $rebuild    Return a rebuilt URL (`true` by dfault - if `false`, the URL components array is returned)
     * @return  string/array    The final URL
     */
    public static function setParameter($var = '', $val = false, $url = false, $rebuild = true)
    {
        $url_entree = $url;
        if (!$url || !is_array($url)) {
            $_url = $url ? $url : self::getRequestUrl();
            $url = self::parse($_url);
        }
        $url['params'][$var] = $val;
        if ($rebuild) {
            return self::build($url);
        }
        return $url;
    }

    /**
     * Rebuild a full URL string from an array of elements
     * 
     * @param array $url_components The array of the URL components:: `scheme`, `user`,
     *                  `pass`, `host`, `port`, `path`, `params`, `hash`
     * @param string/array/boolean $not_toput The name of an array of elements to not include
     * @return string The final URL as a string
     */
    public static function build(array $url_components = null, $not_toput = null)
    {
        if (!is_array($url_components)) {
            return;
        }

        $_ntp = $not_toput ? (is_array($not_toput) ? $not_toput : array($not_toput)) : array();

        if (isset($_urls['params']))
            $_urls['params'] = array_filter($_urls['params']);

        $n_url = 
            ( (isset($url_components['scheme']) && !in_array('scheme', $_ntp)) ? $url_components['scheme'].'://' : 'http://')
            .( (isset($url_components['user']) && !in_array('user', $_ntp)) ? $url_components['user'] : '')
            .( (isset($url_components['pass']) && !in_array('pass', $_ntp)) ? ':'.$url_components['pass'] : '')
            .( ((isset($url_components['user']) && !in_array('user', $_ntp)) || (isset($url_components['pass']) && !in_array('pass', $_ntp))) ? '@' : '')
            .( (isset($url_components['host']) && !in_array('host', $_ntp)) ? $url_components['host'] : '')
            .( (isset($url_components['port']) && !in_array('port', $_ntp)) ? ':'.$url_components['port'] : '')
            .( (isset($url_components['path']) && !in_array('path', $_ntp)) ? $url_components['path'] : '')
            .( (isset($url_components['params']) && !in_array('params', $_ntp)) ? '?'.http_build_query($url_components['params']) : '')
            .( (isset($url_components['hash']) && !in_array('hash', $_ntp)) ? '#'.$url_components['hash'] : '');

        return trim($n_url, '?&');
    }

    /**
     * Validate an URL (without a hash content)
     *
     * @param   string      $url The string to validate
     * @param   array       $protocols Table of Internet protocols to verify (by default : 'http', 'https', 'ftp')
     * @param   bool/string $localhost Is it locally (useful for validating 'http://localhost ...') (FALSE by default) - You can specify a string to check
     * @return  bool        Returns `true` if this is a URL in one of the specified protocols
     */
    public static function isUrl($url = null, $protocols = array('http','https','ftp'), $localhost = false)
    { 
        if (is_null($url) || !$url || !is_string($url)) {
            return false;
        }
        if ($localhost) {
            if (!is_string($localhost)) {
                $localhost = 'localhost';
            }
            if (substr_count($url, $localhost)) {
                return true;
            }
        }
        return (bool) (preg_match("/^[".join('|', $protocols)."]+[:\/\/]+[A-Za-z0-9\-_]+(\\.)?+[A-Za-z0-9\.\/%&=\?\-_]+$/i", $url) > 0);
    }
    
    /**
     * Validate an email address
     *
     * @param   string  $email The string to validate
     * @return  bool    Returns `true` if this is an email
     */
    public static function isEmail($email = null)
    {
        if (is_null($email) || !$email || !is_string($email)) {
            return false;
        }
        return (bool) (preg_match('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email) > 0);
    }

}

// Endfile
