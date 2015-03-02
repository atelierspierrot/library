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


namespace Library;

use \Library\CommandNotFoundException;

/**
 * Largely inspired from <http://github.com/kbjr/Git.php>
 */
class Command
{

// ---------------------
// Cache Management
// ---------------------

    protected $cache = array();
    
    public function addCache($command, $result, $error, $status = 0, $cwd = null, $env = null, $options = null)
    {
        $this->cache[] = array(
            'command'   =>$command,
            'cwd'       =>$cwd,
            'env'       =>$env,
            'options'   =>$options,
            'result'    =>$result,
            'error'     =>$error,
            'status'    =>$status,
        );
    }

    public function isCached($command, $cwd = null)
    {
        foreach ($this->cache as $i=>$cache) {
            if ($cache['command']===$command) {
                if (is_null($cwd)) {
                    return $i;
                } elseif ($cache['cwd']===$cwd) {
                    return $i;
                }
            }
        }
        return false;
    }

    public function getCached($command, $cwd = null)
    {
        $i = $this->isCached($command, $cwd);
        return (false!==$i ? $this->cache[$i] : null);
    }

    public function getCache()
    {
        return $this->cache;
    }

// ---------------------
// Process
// ---------------------

    /**
     * Run a command on a Linux/UNIX system reading it from cache if so
     *
     * @param string $command The command to run
     * @param string $path The path to go to
     * @param bool $force Force the command to really run (avoid caching)
     * @return array An array like ( stdout , status , stderr )
     */
    public function run($command, $path = null, $force = false)
    {
        if (true!==$force && $this->isCached($command, $path)) {
            $cached = $this->getCached($command, $path);
            return array($cached['result'], $cached['status'], $cached['error']);
        }
        return $this->runCommand($command, $path);
    }
    
    /**
     * Run a command on a Linux/UNIX system
     *
     * Accepts a shell command to run
     *
     * @param string $command The command to run
     * @param string $path The path to go to
     * @return array An array like ( stdout , status , stderr )
     */
    public function runCommand($command, $path = null)
    {
        $descriptorspec = array(
            1 => array('pipe', 'w'),
            2 => array('pipe', 'a'),
        );
        $pipes = array();
        $resource = proc_open($command, $descriptorspec, $pipes, $path);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        foreach ($pipes as $pipe) {
            fclose($pipe);
        }

        $status = trim(proc_close($resource));
        $stdout = rtrim($stdout, PHP_EOL);
        $this->addCache($command, $stdout, $stderr, $status, $path);
        return array( $stdout, $status, $stderr );
    }

    /**
     * Get the system path of a command
     *
     * @param string $cmd The command name to retrieve
     * @return string The realpath of the command in the system
     * @throws \Library\CommandNotFoundException if the command doesn't exist
     */
    public static function getCommandPath($cmd)
    {
        $os_cmd = exec('which '.$cmd);
        if (empty($os_cmd)) {
            throw new CommandNotFoundException($cmd);
        }
        return $os_cmd;
    }
    
}

// Endfile
