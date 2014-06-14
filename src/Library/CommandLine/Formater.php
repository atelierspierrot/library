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
 * @author 		Piero Wbmstr <me@e-piwi.fr>
 */
class Formater
{

    /**
     * Mask used to identify a formated tag string like '<bold>string</bold>'
     */
    const FORMAT_PATTERN = '#<([a-z][a-z0-9_=;-]+)>(.*?)</\\1?>#is';

    /**
     * Written messages
     */
	var $messages   =array();

    /**
     * Tabulation and new line aliases
     */
	static $tab        ='    ';
	static $nl         =PHP_EOL;

	private $fetched=false;
	private $user_response=null;
	protected $foreground_color;
	protected $background_color;
	protected $text_options=array();
	protected $autospaced=false;

	/**
	 * The display options
	 */
	protected $options = array(
		'info_options'=>array(
			'foreground'=>'white',
			'background'=>'blue',
		),
		'comment_options'=>array(
			'foreground'=>'black',
			'background'=>'yellow',
		),
		'ok_options'=>array(
			'background'=>'blue',
			'text_options'=>'bold',
		),
		'error_options'=>array(
			'foreground'=>'white',
			'background'=>'red',
			'text_options'=>'bold',
		),
		'error_str_options'=>array(
			'foreground'=>'red',
			'text_options'=>'bold',
		),
		'highlight_options'=>array(
			'background'=>'yellow',
			'autospaced'=>false
		),
		'prompt_options'=>array(
			'foreground'=>'cyan',
			'text_options'=>'bold',
			'autospaced'=>false
		),
		'prompt_default_options'=>array(
			'foreground'=>'yellow',
			'text_options'=>'bold',
			'autospaced'=>false
		),
		'bold_options'=>array(
			'text_options'=>'bold',
			'autospaced'=>false
		),
		'list_title_options'=>array(
			'foreground'=>'green',
			'text_options'=>'bold',
			'autospaced'=>false
		),
		'option_options'=>array(
			'foreground'=>'cyan',
			'text_options'=>'bold',
			'autospaced'=>false
		),
		'var_options'=>array(
			'foreground'=>'yellow',
			'text_options'=>'bold',
			'autospaced'=>false
		),
		'foreground_colors' => array(
            'black'     => 30,
            'red'       => 31,
            'green'     => 32,
            'yellow'    => 33,
            'blue'      => 34,
            'magenta'   => 35,
            'cyan'      => 36,
            'white'     => 37
        ),
		'background_colors' => array(
            'black'     => 40,
            'red'       => 41,
            'green'     => 42,
            'yellow'    => 43,
            'blue'      => 44,
            'magenta'   => 45,
            'cyan'      => 46,
            'white'     => 47
        ),
		'text_options' => array(
            'bold'          => 1,
            'underscore'    => 4,
            'blink'         => 5,
            'reverse'       => 7,
            'conceal'       => 8
        ),
	);

// ------------------------------------
// MAGIC METHODS
// ------------------------------------

	public function __construct(array $options = array())
	{
		if (is_array($options) && count($options)) {
			foreach($options as $k=>$option) {
				$this->addOption($k, $option);
			}
		}
	}

	public function __toString()
	{
		return self::fetch(false);
	}

	public function __destruct()
	{
		if ($this->fetched===false)
			return self::fetch();
	}

	public function addOption($option_name, $option_value)
	{
		if (!isset($this->options[$option_name])) {
			$this->options[$option_name] = $option_value;
		}
	}

// ------------------------------------
// SETTERS/GETTERS
// ------------------------------------

	public function setAutospaced($autospaced = null)
	{
		$this->autospaced = $autospaced;
	}

	public function setForegroundColor($foreground = null)
	{
		if (array_key_exists($foreground, $this->options['foreground_colors'])) {
			$this->foreground_color = $this->options['foreground_colors'][$foreground];
		}
	}

	public function setBackgroundColor($background = null)
	{
		if (array_key_exists($background, $this->options['background_colors'])) {
			$this->background_color = $this->options['background_colors'][$background];
		}
	}

	public function setTextOption($option = null)
	{
		if (array_key_exists($option, $this->options['text_options'])) {
			$this->text_options[] = $this->options['text_options'][$option];
		}
	}

	public function setMessage($text = null, $foreground = null, $background = null, $option = null)
	{
		$this->messages[] = self::format($text, $foreground, $background, $option );
	}

	public function newLine()
	{
		$this->messages[] = self::$nl;
	}

// ------------------------------------
// DISPLAY METHODS
// ------------------------------------

	public function message($text = null, $foreground = null, $background = null, $option = null)
	{
		return $this->format($text, $foreground, $background, $option );
	}

	public function prompt($text = null, $default = null)
	{
		if (!empty($default)) {
			$text .= ' <prompt>[ </prompt>'.self::buildTaggedString( $default, 'prompt_default' ).'<prompt> ]</prompt>';
		}
		$text = self::buildTaggedString( $text, 'prompt' );
		return self::message( self::parse( trim($text).' <prompt>?</prompt> ' ) );
	}

// ------------------------------------
// CONSTRUCT METHODS
// ------------------------------------

	public function buildTaggedString($str, $type = null)
	{
		return '<'.$type.'>'.$str.'</'.$type.'>';
	}

    public function parse($message)
    {
        return preg_replace_callback(self::FORMAT_PATTERN, array($this, '_parseStyle'), $message);
    }

	private function _parseStyle($match)
	{
		if (isset($this->options[strtolower($match[1]).'_options'])) {
			$styles = $this->options[strtolower($match[1]).'_options'];
			return self::format(
				(
					($this->autospaced===true AND (!isset($styles['autospaced']) OR $styles['autospaced']!==false))
					? self::spacedStr($match[2]) : $match[2] ),
				isset($styles['foreground']) ? $styles['foreground'] : null,
				isset($styles['background']) ? $styles['background'] : null,
				isset($styles['text_options']) ? $styles['text_options'] : null
			);
		} elseif (array_key_exists(strtolower($match[1]), $this->options['foreground_colors'])) {
			$styles = $this->options[strtolower($match[1]).'_options'];
			return self::format( $match[2], $match[1] );
		}

		return $match[2];
	}

	public function spacedStr($str, $type = null, $newLines = false)
	{
		$line = str_pad('', strlen(self::$tab.$str.self::$tab));
		return 
			( $newLines===true ? 
				( !is_null($type) ? "<{$type}>" : '' ).$line.( !is_null($type) ? "</{$type}>" : '' ).self::$nl
				: ''
			)
			.( !is_null($type) ? "<{$type}>" : '' ).self::$tab.$str.self::$tab.( !is_null($type) ? "</{$type}>" : '' )
			.( $newLines===true ? 
				self::$nl.( !is_null($type) ? "<{$type}>" : '' ).$line.( !is_null($type) ? "</{$type}>" : '' )
				: ''
			);
	}

	public function format($text = null, $foreground = null, $background = null, $option = null)
	{
		$codes = array();

		if (!empty($foreground)) {
			self::setForegroundColor( $foreground );
			if (!empty($this->foreground_color))
  	        $codes[] = $this->foreground_color;
        }

        if (!empty($background)) {
			self::setBackgroundColor( $background );
			if (!empty($this->background_color))
  	        $codes[] = $this->background_color;
        }

        if (!empty($option)) {
        	$old_options = $this->text_options;
        	$this->text_options = array();
        	if (!is_array($option)) {
        		$option = array( $option );
        	}
        	foreach($option as $_opt) {
        		self::setTextOption( $_opt );
        	}
			if (!empty($this->text_options)) {
          	    $codes = array_merge($codes, $this->text_options);
            }
            $this->text_options = $old_options;
        }

        return sprintf("\033[%sm%s\033[0m", implode(';', $codes), self::parse($text));
	}

	public function fetch($display = true, $exit = false, $last_nl = true)
	{
		$str='';
		if (!empty($this->messages)) {
			foreach($this->messages as $i=>$_message) {
				$str .= $_message
					.( ($i==(count($this->messages)-1) AND $last_nl===false) ? ' ' : self::$nl);
			}
		}
		$this->fetched = true;
		$this->messages = array();
		if ($display===true) {
			echo $str;
			if ($exit===true) exit(0);
		}
		return $str;
	}

}

// Endfile