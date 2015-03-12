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

namespace Library\Session;

use \Library\Session\SessionInterface;

/**
 * Session manager class
 *
 * @author  piwi <me@e-piwi.fr>
 */
class Session
    implements SessionInterface
{

    /**
     * The session attributes application stack
     */
    const SESSION_NAME = 'lib-session';

    /**
     * The session attributes stack
     */
    const SESSION_ATTRIBUTESNAME = 'attributes';

    /**
     * @var array The session contents
     */
    protected $attributes;

    /**
     * @var array Request session backup
     */
    protected $request_attributes;

    /**
     * @var array Full session values table
     */
    protected $session_table;

    /**
     * @var bool The session is opened
     */
    protected $is_opened;

    /**
     * @var bool The session is loaded
     */
    protected $is_loaded;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->request_attributes   = array();
        $this->attributes           = array();
        $this->session_table        = array();
        $this->is_opened            = false;
        $this->is_loaded            = false;
    }

    /**
     * Automatically store new entries at object destruction
     */
    public function __destruct()
    {
        if ($this->isOpened()) {
            $this->commit();
        }
    }

// -----------------------
// Getters / Setters
// -----------------------

    /**
     * Add a session table entry
     *
     * @param string $index
     * @param mixed $value
     * @return self
     */
     public function addSessionTable($index, $value)
     {
        $this->session_table[$index] = $value;
        return $this;
     }

    /**
     * Get the full session table
     *
     * @return array
     */
     public function getSessionTable()
     {
        return $this->session_table;
     }

    /**
     * Get current session ID
     *
     * @return string
     */
    public function getId()
    {
        if ( ! $this->isOpened()) {
            $this->start();
        }
        return session_id();
    }

    /**
     * Get current session name
     *
     * @return string
     */
    public function getName()
    {
        return static::SESSION_NAME;
    }

    /**
     * Test if the current session is already started
     *
     * @return bool
     */
    public function isOpened()
    {
        return true===$this->is_opened;
    }

    /**
     * Test if the current session is already loaded
     *
     * @return bool
     */
    public function isLoaded()
    {
        return true===$this->is_loaded;
    }

// -----------------------
// Session life-cycle
// -----------------------

    /**
     * Start the current session and read it
     *
     * @return self
     */
    public function start()
    {
        if ( ! $this->isOpened()) {
            $this->open();
        }
        if ( ! $this->isLoaded()) {
            $this->read();
        }
        return $this;
    }

    /**
     * Open the current session
     *
     * @return self
     */
    public function open()
    {
        if (version_compare(PHP_VERSION, '5.4', '>')) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        } else {
            @session_start();
        }
        $this->is_opened = true;
        return $this;
    }

    /**
     * Close the current session
     *
     * @return self
     */
    public function close()
    {
        if (version_compare(PHP_VERSION, '5.4', '>')) {
            if (session_status() == PHP_SESSION_ACTIVE) {
                session_write_close();
            }
        } else {
            @session_write_close();
        }
        $this->is_opened = false;
        return $this;
    }

    /**
     * Read the current session contents
     *
     * @return self
     */
    public function read()
    {
        if (isset($_SESSION[static::SESSION_NAME])) {
            $sess_table = $this->_uncrypt($_SESSION[static::SESSION_NAME]);
            if (isset($sess_table[static::SESSION_ATTRIBUTESNAME])) {
                $this->attributes = $sess_table[static::SESSION_ATTRIBUTESNAME];
            }
        }
        $this->addSessionTable(static::SESSION_ATTRIBUTESNAME, $this->attributes);
        $this->is_loaded = true;
        return $this;
    }

    /**
     * Save current session
     *
     * @return self
     */
    public function commit()
    {
        $this->addSessionTable(static::SESSION_ATTRIBUTESNAME, $this->attributes);
        if ($this->isOpened()) {
            $_SESSION[static::SESSION_NAME] = $this->_crypt($this->getSessionTable());
            $this->close();
        }
        return $this;
    }

    /**
     * Destroy current session
     *
     * @return self
     */
    public function clear()
    {
        if ( ! $this->isOpened()) {
            $this->start();
        }
        session_destroy();
        $this->is_opened = false;
        return $this;
    }

    /**
     * Regenrate current session ID
     *
     * @return self
     */
    public function regenerateId()
    {
        if ( ! $this->isOpened()) {
            $this->start();
        }
        session_regenerate_id();
        return $this;
    }

    /**
     * Get all current session values
     *
     * @return array
     */
    public function getAttributes()
    {
        if ( ! $this->isOpened()) {
            $this->start();
        }
        return $this->attributes;
    }

    /**
     * Test if the current session has a parameter
     *
     * @param string $param
     * @return bool
     */
    public function has($param)
    {
        if ( ! $this->isOpened()) {
            $this->start();
        }
        return isset($this->attributes[$param]);
    }

    /**
     * Get current session parameter
     *
     * @param string $param
     * @return mixed
     */
    public function get($param)
    {
        if ( ! $this->isOpened()) {
            $this->start();
        }
        return isset($this->attributes[$param]) ? $this->attributes[$param] : null;
    }

    /**
     * Set current session parameter
     *
     * @param string $param
     * @param mixed $value
     * @return self
     */
    public function set($param, $value)
    {
        if ( ! $this->isOpened()) {
            $this->start();
        }
        $this->attributes[$param] = $value;
        return $this;
    }

    /**
     * Delete a session parameter
     *
     * @param string $param
     * @return self
     */
    public function remove($param)
    {
        if ( ! $this->isOpened()) {
            $this->start();
        }
        if (isset($this->attributes[$param])) {
            unset($this->attributes[$param]);
        }
        return $this;
    }

    /**
     * Get an initial session value
     *
     * @param string $param
     * @return self
     */
    public function getBackup($param)
    {
        if ($this->isOpened()) {
            if (!empty($param)) {
                return isset($this->request_session[$param]) ?
                    $this->request_session[$param] : null;
            }
            return $this->request_session;
        }
    }

    /**
     * Crypt a value
     *
     * @param   string  $_content
     * @return  string
     */
    protected function _crypt($_content)
    {
        return base64_encode(serialize($_content));
    }

    /**
     * Uncrypt a value
     *
     * @param   string  $_content
     * @return  string
     */
    protected function _uncrypt($_content)
    {
        return unserialize(base64_decode($_content));
    }

}

// Endfile