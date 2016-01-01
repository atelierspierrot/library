<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/library>.
 */


namespace Library\Helper;

use \Library\Helper\Arrays as ArrayHelper;

/**
 * Request helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Request as RequestHelper;
 *
 * @author  piwi <me@e-piwi.fr>
 */
class Request
{

    /**
     * Check if the request is sent by command line interface
     *
     * @return bool TRUE if it is so ...
     */
    public static function isCli() 
    {
        return (bool) (php_sapi_name() == 'cli');
    }

    /**
     * Check if the request is sent via AJAX
     *
     * @return bool TRUE if it is so ...
     */
    public static function isAjax()
    {
        return (bool) (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
    }
    
    /**
     * Get the IP address of request device
     *
     * @return string The IP address if found
     */
    public static function getUserIp()
    { 
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Get request headers parameters
     *
     * @return array
     */
    public static function getHeaders()
    {
        $headers = getallheaders();
        return (!empty($headers) ? ArrayHelper::ksort($headers) : array());
    }

    /**
     * Get cookies values
     *
     * @return array|null
     */
    public static function getCookies()
    {
        return (!empty($_COOKIE) ? ArrayHelper::ksort($_COOKIE) : array());
    }

    /**
     * Get getted variables
     *
     * @return array
     */
    public static function getGet()
    {
        return (!empty($_GET) ? ArrayHelper::ksort($_GET) : array());
    }

    /**
     * Get posted variables
     *
     * @return array
     */
    public static function getPost()
    {
        return (!empty($_POST) ? ArrayHelper::ksort($_POST) : array());
    }

    /**
     * Get current user session values
     *
     * @return array
     */
    public static function getSession()
    {
        return (!empty($_SESSION) ? ArrayHelper::ksort($_SESSION) : array());
    }

    /**
     * Get current command line parameters
     *
     * @return array
     */
    public static function getCliParameters()
    {
        global $argv;
        return (!empty($argv) ? ArrayHelper::ksort($argv) : array());
    }

    /**
     * Get current command line running user
     *
     * @return string
     */
    public static function getCurrentCliUser()
    {
        return exec('whoami');
    }

}

