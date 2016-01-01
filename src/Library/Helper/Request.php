<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
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

