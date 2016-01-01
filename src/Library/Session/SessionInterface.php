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


namespace Library\Session;

/**
 * @author  piwi <me@e-piwi.fr>
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
