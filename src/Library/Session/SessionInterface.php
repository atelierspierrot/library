<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\Session;

/**
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
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
	 * Get all curent session values
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
     * @return misc
     */
	public function get($param);

    /**
     * Set current session parameter
     *
     * @param string $param
     * @param misc $value
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