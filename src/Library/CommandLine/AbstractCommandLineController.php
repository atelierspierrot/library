<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library\CommandLine;

use \Library\CodeParser;
use \Library\CommandLine\Helper;
use \Library\CommandLine\Formater;
use \Library\CommandLine\Stream;
use \Library\CommandLine\CommandLineControllerInterface;

/**
 * Basic command line controller
 *
 * Any command line controller must extend this abstract class.
 *
 * It defines some basic command line options that you must not overwrite in your child class:
 *
 * - "h | help" : get a usage information,
 * - "v | verbose" : increase verbosity of the execution (written strings must be handled in your scripts),
 * - "x | debug" : execute the script in "debug" mode (must write actions before executing them),
 * - "q | quiet" : turn verbosity totally off during the execution (only errors and informations will be written),
 * - "f | force" : force some actions (avoid interactions),
 * - "i | interactive" : increase interactivity of the execution,
 * - "version" : get some version information about running environment.
 *
 * @author  Piero Wbmstr <me@e-piwi.fr>
 */
abstract class AbstractCommandLineController
    implements CommandLineControllerInterface
{

    /**
     * This must be over-written in any child class
     *
     * @var string The name of the script
     */
    public static $_name = 'Command line interface';

    /**
     * This must be over-written in any child class
     *
     * @var string The version number of the script
     */
    public static $_version = '1.0.0';

    /**
     * @var string The name of the current called script
     */
    protected $script;

    /**
     * @var array The array of command line parameters
     */
    protected $params;

    /**
     * @var \Library\CommandLine\Formater The formater instance
     */
    protected $formater;

    /**
     * @var \Library\CommandLine\Stream The stream instance
     */
    protected $stream;

    /**
     * @var bool Default if `false`, option `-v` puts it `true`
     */
    protected $verbose      = false;

    /**
     * @var bool Default if `false`, option `-x` puts it `true`
     */
    protected $debug        = false;

    /**
     * @var bool Default if `false`, option `-f` puts it `true`
     */
    protected $force        = false;

    /**
     * @var bool Default if `false`, option `-i` puts it `true`
     */
    protected $interactive  = false;

    /**
     * @var array Stack of executed methods during script execution
     */
    protected $done_methods = array();

    /**
     * @var bool
     */
    protected $written      = false;

    /**
     * The default CLI options
     *
     * @var array
     */
    protected $basic_options = array(
        'argv_options'=>array(
            'h'=>'help',
            'v'=>'verbose',
            'x'=>'debug',
            'q'=>'quiet',
            'f'=>'force',
            'i'=>'interactive',
        ),
        'argv_long_options'=>array(
            'help'=>'help',
            'verbose'=>'verbose',
            'debug'=>'debug',
            'quiet'=>'quiet',
            'force'=>'force',
            'interactive'=>'interactive',
        ),
        'commands'=>array(
            'version'=>'version',
        ),
        'aliases'=>array(
            'vers'=>'version',
        ),
    );

    /**
     * Default CLI & presentation options
     *
     * Must be over-written in any child class, defining:
     *
     * - "title" item: the title of the child class,
     * - "title_options" item: an array for the title presentation options,
     * - "argv_options" item: an array of child class short options like `letter => command` pairs,
     * - "argv_long_options" item: an array of child class long options like `option => command` pairs,
     * - "commands" item: an array of child class commands,
     * - "aliases" item: an array of child class aliases for commands,
     *
     * @var array
     */
    protected $options = array(
        'title'=>'',
        'title_options'=>array(
            'foreground'=>'cyan',
            'background'=>'blue',
            'text_options'=>'bold',
            'autospaced'=>false
        ),
        'argv_options'=>array(),
        'argv_long_options'=>array(),
        'commands'=>array(),
        'aliases'=>array(),
    );

// ------------------------------------
// Construction
// ------------------------------------

    public function __construct(array $options = array())
    {
//        if (!isset($this->options)) $this->options = array();
        $this
            ->_initOptions()
            ->_overWriteOptions($this->basic_options)
            ->_overWriteOptions($options);
//        $this->options = array_merge_recursive($this->basic_options, $this->options, $options);

        if (!empty($options)) {
            foreach($options as $optn=>$optv) {
                if (array_key_exists($optn, $this->options)) {
                    if (is_array($this->options[$optn]) OR is_array($optv)) {
                        if (!is_array($this->options[$optn])) $this->options[$optn] = array( $this->options[$optn] );
                        if (!is_array($optv)) $optv = array( $optv );
                        $this->options[$optn] = array_merge($this->options[$optn], $optv);
                    } else
                        $this->options[$optn] = $optv;
                }
            }
        }

        if (empty($argv)) {
            $argv = $_SERVER['argv'];
        }
        $this->setScript( array_shift($argv) );
        $this->setParameters( $argv );

        $this->formater = new Formater;
        foreach($this->options as $_optn=>$_optv) {
            $this->formater->addOption( $_optn, $_optv );
        }
        $this->formater->setAutospaced(true);

        $this->stream = new Stream;

//        self::_treatBasicOptions();
    }

    /**
     * Magic distribution when printing object
     *
     * @return  string
     */
    public function __toString()
    {
        $this->distribute();
        return '';
    }

    /**
     * Distribution of the work
     */
    public function distribute()
    {
        if (empty($this->params)) {
            return self::writeNothingToDo();
        }
        if ($this->written===false && $this->verbose===true) {
            self::writeIntro();
            $this->written=true;
        }
        self::_treatOptions();
    }

// ------------------------------------
// Setters / Getters
// ------------------------------------

    /**
     * @param   bool    $dbg
     * @return  self
     */
    public function setDebug($dbg = true)
    {
        $this->verbose = $dbg;
        $this->debug = $dbg;
        return $this;
    }

    /**
     * @param bool $vbr
     * @return $this
     */
    public function setVerbose($vbr = true)
    {
        $this->verbose = $vbr;
        return $this;
    }

    /**
     * @param bool $frc
     * @return $this
     */
    public function setForce($frc = true)
    {
        $this->force = $frc;
        return $this;
    }

    /**
     * @param bool $frc
     * @return $this
     */
    public function setInteractive($frc = true)
    {
        $this->interactive = $frc;
        return $this;
    }

    /**
     * @return  $this
     * @see     self::setVerbose()
     * @see     self::setDebug()
     */
    public function setQuiet()
    {
        self::setVerbose(false);
        self::setDebug(false);
        return $this;
    }

    /**
     * @param string $_cls_meth
     * @return $this
     */
    public function addDoneMethod($_cls_meth)
    {
        $this->done_methods[] = $_cls_meth;
        return $this;
    }

    /**
     * @return array
     */
    public function getDoneMethods()
    {
        return $this->done_methods;
    }

    /**
     * Set the current command line script called
     *
     * @param string $script_name The script name
     * @return self
     */
    public function setScript($script_name)
    {
        $this->script = $script_name;
        return $this;
    }

    /**
     * Get the current command line script called
     *
     * @return  string
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Set the command line parameters
     *
     * @param array $params The collection of parameters
     * @return self
     */
    public function setParameters(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Get the parameters collection
     *
     * @return  array
     */
    public function getParameters()
    {
        return $this->params;
    }

// ------------------------------------
// CLI METHODS
// ------------------------------------
// The docblocks comments of any `run...` method is used to build the "help" info of the script

    /**
     * List of all options and features of the command line tool ; for some commands, a specific help can be available, running <var>--command --help</var>
     * Some command examples are purposed running <var>--console --help</var>
     */
    public function runHelpCommand($opt = null)
    {
        if (!empty($opt)) {
            if (!is_array($opt)) $opt = array( $opt=>'' );
            $opt_keys = array_keys($opt);
            $ok=false;
            while ($ok===false) {
                if (count($opt_keys)==0) break;
                $current_option = array_shift( $opt_keys );
                $ok = self::runArgumentHelp( $current_option );
            }
            $this->debugWrite( '>> [help] displaying global help' );
            $help_str = Helper::getHelpInfo($this->options, $this->formater, $this);
            self::write( $this->formater->parse($help_str) );
            self::writeStop();
        } else {
            self::usage();
        }
    }

    /**
     * Run the command line in <bold>verbose</bold> mode, writing some information on screen (default is <option>OFF</option>)
     */
    public function runVerboseCommand()
    {
        self::setVerbose();
    }

    /**
     * Run the command line in <bold>quiet</bold> mode, trying to not write anything on screen (default is <option>OFF</option>)
     */
    public function runQuietCommand()
    {
        self::setQuiet();
    }

    /**
     * Run the command line in <bold>debug</bold> mode, writing some scripts information during runtime (default is <option>OFF</option>)
     */
    public function runDebugCommand()
    {
        self::setDebug();
    }

    /**
     * Run the command line in <bold>forced</bold> mode ; any choice will be set on default value if so
     */
    public function runForceCommand()
    {
        self::setForce();
    }

    /**
     * Run the command line in <bold>interactive</bold> mode ; any choice will be prompted if possible
     */
    public function runInteractiveCommand()
    {
        self::setInteractive();
    }

    /**
     * Get versions of system environment
     */
    public function runVersionCommand()
    {
        $str = '';
        $_cls = get_class($this);
        if (!empty($_cls::$_name)) {
            $str .= $_cls::$_name;
        }
        if (!empty($_cls::$_version)) {
            $str .= ' - v. '.$_cls::$_version;
        }
        if (strlen($str)) {
            $this->writeInfo('Interface version: <bold>'.$str.'</bold>');
        }
        $this->writeInfo('PHP version: <bold>'.phpversion().'</bold>');
        $this->writeInfo('Server software: <bold>'.php_uname().'</bold>');
        $this->writeStop();
    }

// ------------------------------------
// CLI WRITING METHODS
// ------------------------------------

    /**
     * Format and write a string to STDOUT
     *
     * @param   null    $str
     * @param   bool    $new_line
     * @return  $this
     */
    public function write($str = null, $new_line = true)
    {
        $this->stream->write( $this->formater->message($str), $new_line );
        return $this;
    }

    /**
     * Format and write an error message to STDOUT (or STDERR) and exits with status `$status`
     *
     * @param   null    $str
     * @param   int     $status
     * @param   bool    $new_line
     * @return  $this
     */
    public function error($str = null, $status = 1, $new_line = true)
    {
        $this->stream->error( $this->formater->message($str), $status, $new_line );
        return $this;
    }

    /**
     * Parse, format and write a message to STDOUT
     *
     * @param   string  $str
     * @param   null    $type
     * @param   bool    $spaced
     * @return  $this
     */
    public function parseAndWrite($str, $type = null, $spaced = false)
    {
        if ($this->verbose!==true) return self::write( $str );
        if ($spaced!==false)
            $str = $this->formater->spacedStr( $str, $type, true );
        elseif (!is_null($type))
            $str = $this->formater->buildTaggedString( $str, $type );
        self::write( $this->formater->parse( $str ) );
        return $this;
    }

    /**
     * @param   string  $str
     * @return  $this
     */
    public function writeError($str)
    {
        if ($this->verbose===true) self::writeBreak();
        self::parseAndWrite( $str, 'error', true );
        return $this;
    }

    /**
     * @param   string  $str
     * @return  $this
     */
    public function writeThinError($str)
    {
        if ($this->verbose===true) self::writeBreak();
        self::parseAndWrite( $str, 'error_str' );
        if ($this->verbose===true) self::writeBreak();
        return $this;
    }

    /**
     * @param   string  $str
     * @return  $this
     */
    public function writeInfo($str)
    {
        self::parseAndWrite( $str, 'info' );
        return $this;
    }

    /**
     * @param   string  $str
     * @return  $this
     */
    public function writeComment($str)
    {
        if ($this->verbose===true)
        self::parseAndWrite( $str, 'comment' );
        return $this;
    }

    /**
     * @param   string  $str
     * @return  $this
     */
    public function writeHighlight($str)
    {
        self::parseAndWrite( $str, 'highlight' );
        return $this;
    }

    /**
     * @return  $this
     */
    public function writeBreak()
    {
        if ($this->verbose===true) $this->stream->write(PHP_EOL);
        return $this;
    }

    /**
     * @return  $this
     */
    public function writeStop()
    {
        if ($this->verbose!==true) {
            $this->stream->__exit();
        } else {
            $this->debugWrite( '>> [writeStop] exit with no error' );
            $this->stream->__exit(
                $this->formater->message( '<info>-- out --</info>' )
            );
        }
        return $this;
    }

    /**
     * Write a string only in verbose mode
     *
     * @param   null    $str
     * @param   bool    $new_line
     * @return  $this
     */
    public function verboseWrite($str = null, $new_line = true)
    {
        if ($this->verbose===true) $this->write( $str, $new_line );
        return $this;
    }

    /**
     * Write a string only in debug mode
     *
     * @param   null $str
     * @param   bool $new_line
     * @return  $this
     */
    public function debugWrite($str = null, $new_line = true )
    {
        if ($this->debug===true) $this->write( $str, $new_line );
        return $this;
    }

    /**
     * Prompt user input
     *
     * @param   string|null $str
     * @param   mixed|null  $default
     * @return  string
     */
    public function prompt($str = null, $default = null)
    {
        $this->stream->prompt(
            $this->formater->prompt($str, $default)
        );
        return $this->stream->getUserResponse();
    }

    /**
     * Get last user input
     *
     * @return  string
     */
    public function getPrompt()
    {
        return $this->stream->getUserResponse();
    }

// ------------------------------------
// CONTROLLER METHODS
// ------------------------------------

    private function __init()
    {
        if ($this->written===false && $this->verbose===true) {
            self::writeIntro();
            $this->written=true;
        }
    }

    public function writeIntro()
    {
        $str = !empty($this->options['title']) ? $this->options['title'] : $this->getVersionString();
        $this->stream->write(
            $this->formater->parse(
                $this->formater->spacedStr($str, 'title', true)
            ).PHP_EOL
        );
    }

    public function getVersionString()
    {
        $str = '';
        $_cls = get_class($this);
        if (!empty($_cls::$_name)) {
            $str .= $_cls::$_name;
        }
        if (!empty($_cls::$_version)) {
            $str .= ' - v. '.$_cls::$_version;
        }
        return $str;
    }

    public function writeNothingToDo(  )
    {
        self::__init();
        self::writeThinError( '> Nothing to do ! (run "--help" option to see help)' );
        $this->stream->__exit();
    }

    public function runArgumentHelp($arg = null)
    {
        $help_descr = $this->getOptionHelp( $arg );
        if ($help_descr!=$arg) {
            $this->debugWrite( ">> [help] displaying help for option \"$arg\"" );
            $help_ctt = Helper::formatHelpString( ucfirst($arg), $help_descr, $this->formater );
            self::write( $this->formater->parse($help_ctt) );
            self::writeStop();
        }
        $this->debugWrite( ">> [help] no help found for option \"$arg\"" );
        return false;
    }

    public function usage($opt = null)
    {
        if (!empty($opt)) {
            if (!is_array($opt)) $opt = array( $opt=>'' );
            $opt_keys = array_keys($opt);
            $ok=false;
            while ($ok===false) {
                if (count($opt_keys)==0) break;
                $current_option = array_shift( $opt_keys );
                $ok = self::runArgumentHelp( $current_option );
            }
        }
        $this->debugWrite( '>> [help] displaying global help' );
        $help_str = Helper::getHelpInfo($this->options, $this->formater, $this);
        self::write( $this->formater->parse($help_str) );
        self::writeStop();
    }

// --------------------
// PROCESS
// --------------------

    protected function _initOptions()
    {
        if (!isset($this->options)) $this->options = array();
        return $this;
    }

    protected function _overWriteOptions(array $options)
    {
        if (!empty($options)) {
            foreach ($options as $name=>$stack) {
                if (is_array($stack)) {
                    if (!isset($this->options[$name])) {
                        $this->options[$name] = array();
                    }
                    $this->options[$name] = array_merge($this->options[$name], $stack);
                } else {
                    $this->options[$name] = $stack;
                }
            }
        }
        return $this;
    }

    protected function _treatOptions()
    {
        $this->params = self::getopt();
        $this->debugWrite( ">> Command line arguments are [".var_export($this->params,1)."]" );

        $_meths=array();
        foreach($this->params as $_opt_name=>$_opt_val) {
            $new_opt_names = array(
                $_opt_name, $_opt_name.':', $_opt_name.'::'
            );

            foreach($new_opt_names as $_opt_new_name) {
                if (array_key_exists($_opt_new_name, $this->options['argv_options'])) {
                    $_ind = $this->options['argv_options'][$_opt_new_name];
                    $_meths[ $_ind ] = $_opt_val;
                }
                if (array_key_exists($_opt_new_name, $this->options['argv_long_options'])) {
                    $_ind = $this->options['argv_long_options'][$_opt_new_name];
                    $_meths[ $_ind ] = $_opt_val;
                }
                if (array_key_exists($_opt_new_name, $this->options['commands'])) {
                    $_ind = $this->options['commands'][$_opt_new_name];
                    $_meths[ $_ind ] = $_opt_val;
                }
                if (array_key_exists($_opt_new_name, $this->options['aliases'])) {
                    $_ind = $this->options['aliases'][$_opt_new_name];
                    $_meths[ $_ind ] = $_opt_val;
                }
            }
        }

        if (!empty($_meths)) {

            if (array_key_exists('help', $_meths)) {
                $_cls_meth = 'runHelpCommand';
                $args = $_meths;
                unset($args['help']);
                self::addDoneMethod( $_cls_meth );
                self::__init();
                $this->$_cls_meth( $args );
            } else {
                foreach($_meths as $_meth=>$_args) {
                    $_cls_meth = 'run'.ucfirst($_meth).'Command';
                    if (method_exists($this, $_cls_meth) AND !in_array($_cls_meth, $this->done_methods)) {
                        self::addDoneMethod( $_cls_meth );
                        self::__init();
                        $this->$_cls_meth( $_args );
                    }
                }
            }

        }
    }

    protected function _treatBasicOptions()
    {
        return Helper::treatOptions($this->basic_options, $this);
    }

    public function getopt()
    {
        return Helper::getopt( array_merge(
            $this->basic_options, $this->options
        ) );
    }

    public function getOptionMethod($arg = null)
    {
        return Helper::getOptionMethod(array_merge(
            $this->basic_options, $this->options
        ), $this);
    }

    public function getOptionDescription($arg = null)
    {
        return Helper::getOptionDescription($arg, $this);
    }

    public function getOptionHelp($arg = null)
    {
        return Helper::getOptionHelp($arg, $this);
    }

}

// Endfile