<?php
/**
 * This file is part of the Library package.
 *
 * Copyright (c) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
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

/**
 * Request helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Request as RequestHelper;
 *
 * @author      Piero Wbmstr <me@e-piwi.fr>
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

}

// Endfile
