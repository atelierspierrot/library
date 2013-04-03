<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library;

/**
 * Largely inspired from <http://github.com/kbjr/Git.php>
 */
class Command
{

// ---------------------
// Cache Management
// ---------------------

    protected $cache = array();
    
    public function addCache($command, $result, $error, $cwd = null, $env = null, $options = null)
    {
        $this->cache[] = array(
            'command'=>$command, 'cwd' => $cwd, 'env' => $env, 'options' => $options,
            'result'=>$result, 'error'=>$error
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
	 * Run a command on a Lilnux/UNIX system reading it from cache if so
	 *
	 * @param string $command The command to run
	 * @param string $path The path to go to
	 * @param bool $force Force the command to really run (avoid caching)
	 * @return array An array like ( result , error , status )
	 */
	public function run($command, $path = null, $force = false)
	{
	    if (true!==$force && $this->isCached($command, $path)) {
	        return $this->getCached($command, $path);
	    }
	    return $this->runCommand($command, $path);
	}
	
	/**
	 * Run a command on a Lilnux/UNIX system
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
			2 => array('pipe', 'w'),
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
		$this->addCache($command, $stdout, $stderr, $path);
		return array( $stdout, $status, $stderr );
	}

    /**
     * Get the system path of a command
     *
     * @param string $cmd The command name to retrieve
     * @return string The realpath of the command in the system
     * @throw Throws a `CommandNotFoundExcpetion` if the command doesn't exist
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