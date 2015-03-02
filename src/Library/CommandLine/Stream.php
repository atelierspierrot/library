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


namespace Library\CommandLine;

/**
 * Command line streams manager
 *
 * Use this class to write on `STDOUT` or `STDERR` and read from `STDIN`.
 *
 * @author  Piero Wbmstr <me@e-piwi.fr>
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