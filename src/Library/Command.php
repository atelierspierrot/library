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
