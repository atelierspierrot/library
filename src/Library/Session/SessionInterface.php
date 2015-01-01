<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (ↄ) 2013-2015 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
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
 */

namespace Library\Session;

/**
 * @author  Piero Wbmstr <me@e-piwi.fr>
 */
interface SessionInterface
{

    /**
     * Start the current session and load it
     */
    public function start();

    /**
     * Open the current session
     */
    public function open();

    /**
     * Close the current session
     */
    public function close();

    /**
     * Test if the current session is already started
     *
     * @return bool
     */
    public function isOpened();

    /**
     * Read current session contents
     */
    public function read();

    /**
     * Test if the current session is already loaded
     *
     * @return bool
     */
    public function isLoaded();

    /**
     * Save current session contents
     */
    public function commit();

    /**
     * Destroy current session
     */
    public function clear();

    /**
     * Regenrate current session ID
     */
    public function regenerateId();

    /**
     * Get current session ID
     *
     * @return string
     */
    public function getId();

    /**
     * Get current session name
     *
     * @return string
     */
    public function getName();

    /**
     * Get all current session values
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Test if the current session has a parameter
     *
     * @param string $param
     * @return bool
     */
    public function has($param);

    /**
     * Get current session parameter
     *
     * @param string $param
     * @return mixed
     */
    public function get($param);

    /**
     * Set current session parameter
     *
     * @param string $param
     * @param mixed $value
     */
    public function set($param, $value);

    /**
     * Delete a session parameter
     *
     * @param string $param
     */
    public function remove($param);

}

// Endfile