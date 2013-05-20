<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library;

use Psr\Log\LoggerInterface,
    Psr\Log\AbstractLogger,
    Psr\Log\LogLevel;

/**
 * Write some log infos in log files
 *
 * For compliance, this class implements the [PSR Logger Interface](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md).
 *
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Logger
    extends AbstractLogger
    implements LoggerInterface
{

	protected $logname;
	protected $log_message;

	private static $rotate = 0;
	private static $isInited = false;

	protected static $config = array(
		'minimum_log_level' => 0,
		'directory' => '',
		'max_log' => 100,
		'logfile' => 'history',
		'errorlogfile' => 'error',
		'maxsize_log' => 1000,
		'logfile_extension' => 'log',
		'datetime_format' => 'd-m-Y H:i:s',
		'duplicate_errors' => true,
	);

    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';
    protected static $levels = array(
        'emergency'     =>800,
        'alert'         =>700,
        'critical'      =>600,
        'error'         =>500,
        'warning'       =>400,
        'notice'        =>300,
        'info'          =>200,
        'debug'         =>100
    );

// -----------------
// Object handlers
// -----------------

	/**
	 * Creation of a new logger entry
	 *
	 * @param array $user_options A set of one shot options
	 * @see self::init()
	 */
	public function __construct(array $user_options=array())
	{
		$this->init($user_options);
	}

    /**
     * Logs with an arbitrary level.
     *
	 * @param int $level A type for the message, must be a class constant
	 * @param string $message The message info to log
	 * @param array $context An optional context array for the message
	 * @param string $logname A logfile name to write in a specific one
     * @return null
     */
    public function log($level, $message, array $context = array(), $logname = null)
    {
		self::init(array(),$logname);
		if (!$this->isKnownLevel($level)) {
			throw new \InvalidArgumentException( 
				sprintf('Unknown level "%s" for a log message!', $level)
			);
		}
		if ($this->getLevelCode($level) < $this->minimum_log_level) return false;
		return self::addRecord($level, $message, $context);
    }

	/**
	 * Load the configuration infos
	 */
	protected function init(array $user_options=array(), $logname = null)
	{
		$this->logname = $logname;
		if (true===self::$isInited) return;
		foreach ($user_options as $_static=>$_value) {
			$this->$_static = $_value;
		}
		self::$isInited = true;
	}

	/**
	 * Allows to set a property or a configuration entry like : $logger->config_name = $val
	 *
	 * @param string $var A property name or an entry of the $config class static
	 * @param misc $val The value to set
	 * @throw Throws an InvalidArgument exception if so
	 */
	public function __set($var, $val)
	{
		if (property_exists($this, $var))
			$this->$var = $val;
		elseif (isset(self::$config[$var]))
			self::$config[$var] = $val;
		else
			throw new \InvalidArgumentException( 
				sprintf('Trying to set an unknown variable "%s" in "%s" class!', $var, __CLASS__)
			);
	}

	/**
	 * Allows to call a configuration entry like : $logger->config_name
	 *
	 * @param string $var A property name or an entry of the $config class static
	 * @return misc The value if found, null otherwise
	 */
	public function __get($var)
	{
		if (property_exists($this, $var))
			return $this->$var;
		if (isset(self::$config[$var]))
			return self::$config[$var];
		return null;
	}

    /**
     * Interpolates context values into the message placeholders.
	 *
     * @param string $message The original message string, with placeholders constructed as `{variable_name}`
     * @param array $context The context where must be found the placeholders values
     * @param bool $silent Does the function must be silent or not ; if not, an Exception is thrown if one of the context
     *                      array values can't be written as a string
     * @return string The message with placeholders replacements
     */
    public static function interpolate($message, array $context = array(), $silent = false)
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if ($key==='exception' && ($val instanceof \Exception)) {
                $str_val = $val->getMessage();
            } else {
                try {
                    $str_val = (string) $val;
                } catch(\Exception $e) {
                    if (!$silent) {
                        throw new \RuntimeException( 
                            sprintf(
                                'Variable named "%s" of logging message context can\'t be written as string (get type "%s")!', 
                                $key, (is_object($val) ? get_class($val) : gettype($val))
                            )
                        );
                    }
                }
            }
            $replace['{' . $key . '}'] = $str_val;
        }
        return strtr($message, $replace);
    }

// -----------------
// Logging
// -----------------

	/**
	 * Create a new log record and writes it
	 *
	 * @param string $message The message info to log
	 * @param int $level A type for the message, must be a class constant
	 * @param array $context An optional context array for the message
	 */
	protected function addRecord($level, $message, array $context = array())
	{
	    $message = $this->interpolate($message, $context);
		$record = array(
            'message' => (string) $message,
            'context' => $context,
            'level' => self::getLevelCode($level),
            'level_name' => strtoupper($level),
            'datetime' => new \DateTime(),
            'ip' => self::getUserIp(),
			'pid' => @getmypid(),
            'extra' => array(),
		);
		return self::write( self::getLogRecord( $record ), $level );
	}

	/**
	 * Write a new line in the logfile
	 *
	 * @param string $line The formated line to write in log file
	 * @param int $level The level of the current log info (default is 100)
	 */	
	protected function write($line, $level = 100)
	{
		$logfile = $this->getFilePath( $level );

		if ($this->mustRotate( $logfile )) {
			self::$rotate = $this->max_log;
			$this->rotate( $logfile );
		}

		$return = self::writeInFile($logfile, $line);

		if ($this->isErrorLevel($level) && true===$this->duplicate_errors)
			self::write( $line, 100 );
		
		return $return;
	}

	/**
	 * Rotate log files if so
	 *
	 * @param string $logfile The log filename to check
	 */
	protected function rotate($logfile)
	{
		if (self::$rotate-- > 0) {
			self::writeInFile($logfile, "[-- rotate --]");

			@unlink($logfile.'.'.self::$rotate);
			while (self::$rotate-- > 0) {
				@rename(
					$logfile.(self::$rotate ? '.'.self::$rotate : ''), 
					$logfile.'.'.(self::$rotate + 1)
				);
			}
		}
	}

// -----------------
// Log string builders
// -----------------

	/**
	 * Is the current log level a class constant
	 *
	 * @param int $level A type for the message, must be a class constant
	 * @return bool True if the level is known
	 */
	protected function isKnownLevel($level)
	{
		return array_key_exists($level, self::$levels);
	}

	/**
	 * Is the current log level an error one 
	 *
	 * @param int $level A type for the message, must be a class constant
	 * @return bool True if the level is more than a warning
	 */
	protected function isErrorLevel($level)
	{
	    if (!is_numeric($level)) $level = $this->getLevelCode($level);
		return (bool) ($level>300);
	}

	/**
	 * Is the current logfile needs to be rotated
	 *
	 * @param string $logfile The log filename to check
	 * @return bool True if the logfile must be rotated
	 */
	protected function mustRotate($logfile)
	{
		$s = @filesize($logfile);
		$l = count(@file($logfile));
		if ( @file_exists($logfile) && @is_readable($logfile) && $s > $this->maxsize_log * 1024)
			return true;
		else
			return false;
	}

	/**
	 * Get a level name
	 *
	 * @param int $level A type for the message, must be a class constant
	 * @return string The level name if found, '--' otherwise
	 */
	protected function getLevelName($level)
	{
		return in_array($level, self::$levels) ? array_search($level, self::$levels) : '--';
	}

	/**
	 * Get a level code
	 *
	 * @param int $level A type for the message, must be a class constant
	 * @return string The level code if found, `0` otherwise
	 */
	protected function getLevelCode($level)
	{
		return array_key_exists($level, self::$levels) ? self::$levels[$level] : 0;
	}

	/**
	 * Build a log line from a complex array
	 *
	 * @param array $record An array built by the "addRecord()" method
	 * @return string The line formated and ready to be written
	 * @see self::addRecord()
	 */
	protected function getLogRecord($record)
	{
		$prefix = $suffix = array();
		
		// first infos
		$date = $record['datetime']->format( $this->datetime_format );
		$prefix[] = '['.$date.']';
		if (!empty($record['ip']))
			$prefix[] = '[ip '.$record['ip'].']';
		if (!empty($record['pid']))
			$prefix[] = '[pid '.$record['pid'].']';
		$prefix[] = $record['level_name'].' :';

		// last infos
		$suffix[] = '['.$this->writeArray($record['context']).']';
		$suffix[] = '['.$this->writeArray($record['extra']).']';
		
		return join(' ', $prefix).' '.preg_replace("/\n*$/", ' ', $record['message']).' '.join(' ', $suffix);
	}

	/**
	 * Get the log file path
	 *
	 * @param int $level The level of the current log info (default is 100)
	 * @return string The absolute path of the logfile to write in
	 */
	protected function getFilePath($level = 100)
	{
		return rtrim($this->directory, '/').'/'.$this->getFileName($level)
			.'.'.trim($this->logfile_extension, '.');
	}

	/**
	 * Get the log file name
	 *
	 * @param int $level The level of the current log info (default is 100)
	 * @return string The logfile name to write in
	 */
	protected function getFileName($level = 100)
	{
		return !empty($this->logname) ? $this->logname : ( $this->isErrorLevel($level) ? $this->errorlogfile : $this->logfile );
	}

// -----------------
// Utilities
// -----------------

	/**
	 * Get the user IP address
	 */
	public static function getUserIp()
	{ 
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
		elseif (isset($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		else
			$ip = $_SERVER['REMOTE_ADDR'];
		return $ip;
	}

	/**
	 * Write an array on one line
	 *
	 * @param array The array to write
	 * @return string The formated string
	 */
	public static function writeArray($array)
	{ 
		if (empty($array)) $str = '';
		else {
			$str = serialize($array);
		}
		return $str;
	}

	/**
	 * Write a string in a file
	 *
	 * @param string $file The filename to write in
	 * @param string $line The line to add in the file
	 * @return bool True if the line had been written
	 */
	public static function writeInFile($file, $line)
	{
		$f = @fopen($file, "ab");
		if ($f) {
			fputs($f, "\n".str_replace('<', '&lt;', $line));
			fclose($f);
			return true;
		}
		else
			return false;
	}

}

/*
class TestClass
{
    var $msg;
    function __construct( $str )
    {
        $this->msg = $str;
    }
    function __toString()
    {
        return $this->msg;
    }
}

// test of global logger
$logger = getContainer()->get('logger');
var_export($logger);

// write a simple log
$ok = getContainer()->get('logger')->log($logger::DEBUG, 'my message');
var_export($ok);

// write a log message with placeholders
$ok = getContainer()->get('logger')->log($logger::DEBUG, 'my message with placeholders : {one} and {two}', array(
    'one' => 'my value for first placeholder',
    'two' => new TestClass( 'my test class with a toString method' )
));
var_export($ok);

// write logs in a specific "test" file
$ok = getContainer()->get('logger')->log($logger::DEBUG, 'my message', array(), 'test');
var_export($ok);

// write many logs
for ($i=0; $i<1000; $i++)
{
    $ok = getContainer()->get('logger')->log( \App\Logger::DEBUG, '[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
    $ok = getContainer()->get('logger')->log( \App\Logger::ERROR, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
    $ok = getContainer()->get('logger')->log( \App\Logger::INFO, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ', $_GET, 'test' );
}

// write error logs
		try{
//			fopen(); // error
			if (2 != 4) // false
				throw new \App\Exception("Capture l'exception par défaut", 12);
		} catch(\App\Exception $e) {
			echo $e;
		}
*/

// Endfile