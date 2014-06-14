<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library\Session;

use \Library\Session\Session;

/**
 * Session manager class
 *
 * @author 		Piero Wbmstr <me@e-piwi.fr>
 */
class FlashSession
    extends Session
{

	/**
	 * The session flash messages stack
	 */
	const SESSION_FLASHESNAME = 'flashes';

	/**
	 * @var array The session flash attributes (available just for one request)
	 */
	protected $flashes;

	/**
	 * @var array The request session flash attributes (no more available in session)
	 */
	protected $old_flashes;

	/**
	 * Constructor
	 */
	public function __construct()
	{
	    parent::__construct();
        $this->flashes      = array();
        $this->old_flashes  = array();
	}

	/**
	 * Start the current session if so
     *
     * @return self
	 */
	public function read() 
	{
	    parent::read();
        if (isset($_SESSION[static::SESSION_NAME])) {
        	$sess_table = $this->_uncrypt($_SESSION[static::SESSION_NAME]);
            if (isset($sess_table[static::SESSION_FLASHESNAME])) {
                $this->old_flashes = $sess_table[static::SESSION_FLASHESNAME];
            }
        }
        $this->addSessionTable(static::SESSION_FLASHESNAME, $this->old_flashes);
        return $this;
	}

    /**
     * Save current session
     *
     * @return self
     */
	public function commit()
	{
        $this->flashes = array_diff_key($this->flashes, $this->old_flashes);
        $this->addSessionTable(static::SESSION_FLASHESNAME, $this->flashes);
        parent::commit();
        return $this;
	}

    /**
     * Test if current session has flash parameters
     *
     * @return bool
     */
	public function hasFlash() 
	{
        if ( ! $this->isOpened()) {
            $this->start();
        }
		return !empty($this->old_flashes);
	}

    /**
     * Get a current session flash parameter
     *
     * @param string $index
     * @return misc
     */
	public function getFlash($index)
	{
        if ( ! $this->isOpened()) {
            $this->start();
        }
		$_oldf = null;
    	if (!empty($index) && isset($this->old_flashes[$index])) {
			$_oldf = $this->old_flashes[$index];
			unset($this->old_flashes[$index]);
			return $_oldf;
        }
		return null;
	}

    /**
     * Set a current session flash parameter
     *
     * @param misc $value
     * @param string $index
     * @return self
     */
	public function setFlash($value, $index = null)
	{
        if ( ! $this->isOpened()) {
            $this->start();
        }
		if (!empty($index)) {
			$this->flashes[$index] = $value;
		} else {
			$this->flashes[] = $value;
		}
		return $this;
	}

    /**
     * Get current session flash parameters stack
     *
     * @return array
     */
	public function allFlashes()
	{
        if ( ! $this->isOpened()) {
            $this->start();
        }
        if (!empty($this->old_flashes)) {
    		$_oldf = $this->old_flashes;
    		$this->old_flashes = array();
			return $_oldf;
        }
		return null;
	}

    /**
     * Delete current session flash parameters
     *
     * @return self
     */
	public function clearFlashes()
	{
        if ( ! $this->isOpened()) {
            $this->start();
        }
		$this->flashes = array();
		$this->old_flashes = array();
		return $this;
	}

}

// Endfile