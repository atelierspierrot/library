<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library\CommandLine;

/**
 * Command line streams manager
 *
 * Use this class to write on `STDOUT` or `STDERR` and read from `STDIN`.
 *
 * @author 		Piero Wbmstr <me@e-piwi.fr>
 */
class Stream
{

    /**
     * STDOUT stream for writing
     */
	public $stream;

    /**
     * STDERR stream for writing
     */
	public $error;

    /**
     * STDIN stream for reading
     */
	public $input;

    /**
     * User input in STDIN when a prompt is launched
     */
	private $user_response;

// ------------------------------------
// MAGIC METHODS
// ------------------------------------

    /**
     * The three streams are initiated
     *
     * They are created with the defaults `STDIN`, `STDOUT` and `STDERR` if present or 
     * opened as file streams otherwise.
     */
	public function __construct()
	{
		$this->stream = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w');
		$this->input = defined('STDIN') ? STDIN : fopen('php://stdin', 'r');
		$this->error = defined('STDERR') ? STDERR : fopen('php://stderr', 'w');
	}

    /**
     * Exit the script execution
     *
     * @param string $str Optional string to write before exit
     * @return void
     */
	public function __exit($str = null)
	{
		if ($str) self::write( $str );
		exit;
	}

    /**
     * Write a message on `STDERR` and exit with an error status
     *
     * @param string $str The error message string to write
     * @param int $status The error status for exit (default is `1`) ; you can set it on `0` to not exit
     * @param bool $new_line Pass a new line befor exit (default is `true`)
     * @return void
     */
	public function error($str, $status = 1, $new_line = true)
	{
        fwrite($this->error, $str.( true===$new_line ? PHP_EOL : '' ));
        fflush($this->error);
        if ($status>0) exit($status);
	}

    /**
     * Write a message on `STDOUT`
     *
     * @param string $str The error message string to write
     * @param bool $new_line Pass a new line befor exit (default is `true`)
     * @return void
     */
	public function write($str, $new_line = true)
	{
        fwrite($this->stream, $str.( true===$new_line ? PHP_EOL : '' ));
        fflush($this->stream);
	}

    /**
     * Write a message on `STDOUT` and wait for a user input on `STDIN`
     *
     * @param string $str The error message string to write
     * @return void
     */
	public function prompt($str)
	{
		self::write( $str, false );
		$this->user_response = trim( fgets( $this->input, 4096 ) );
	}

    /**
     * Get last user input on `STDIN`
     *
     * @return string The last user input from `STDIN`
     */
	public function getUserResponse()
	{
		return $this->user_response;
	}

}

// Endfile