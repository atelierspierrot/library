<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library;

use Library\AbstractReporterAdapter;

/**
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Reporter
{

    /**
     * Set the reporter to return each output rendering
     */
	const OUTPUT_BY_LINE = 1;

    /**
     * Set the reporter to append each output rendering to a global output
     */
	const OUTPUT_APPEND = 2;

    /**
     * The reporter global output
     * @var string
     */
	protected $output;

    /**
     * The reporter flag (must be one of the class OUTPUT constants)
     * @var int
     */
	protected $flag;

    /**
     * The reporter adapter
     * @var object An adapter from the `Library\ReprterAdapter` namespace
     */
	protected $__adapter;

    /**
     * Table of the default tag types the adapters must implement
     * @var array
     */
     public static $default_tag_types = array(
        'default',
        'unordered_list',
        'ordered_list',
        'table',
        'definition',
        'code',
        'pre_formated',
        'title',
        'paragraph',
        'citation',
        'bold',
        'italic',
        'link',
     );

    /**
     * Table of the default masks the adapters must implement as constants
     * @var array
     */
      public static $default_masks = array(
        'default',
	    'new_line',
	    'tab',
	    'key_value',
        'unordered_list',
        'unordered_list_item',
        'ordered_list',
        'ordered_list_item',
        'table',
        'table_title',
        'table_head',
        'table_head_line',
        'table_head_cell',
        'table_body',
        'table_body_line',
        'table_body_cell',
        'table_foot',
        'table_foot_line',
        'table_foot_cell',
        'definition',
        'definition_term',
        'definition_description',
        'code',
        'pre_formated',
        'title',
        'paragraph',
        'citation',
        'bold',
        'italic',
        'link',
     );

	/**
	 * Construction of a new Reporter object
	 *
	 * @param null|string $adapter_type The adapter type name
	 */
	public function __construct($adapter_type = 'html', $flag = self::OUTPUT_BY_LINE)
	{
		$this->setAdapterType($adapter_type);
		$this->setFlag($flag);
	}

	/**
	 * Reset all object properties to default or empty values
	 *
	 * @param bool $hard Reset all object properties (adapter included)
	 * @return self $this for method chaining
	 */
	public function reset($hard = false)
	{
		$this->output = '';
		if (true===$hard) {
			$this->__adapter_type = null;
			$this->__adapter = null;
		}
		return $this;
	}

	/**
	 * Returns the object global output
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getOutput();
	}

// -------------------------
// Getters / Setters
// -------------------------

	/**
	 * Set the reporter flag
	 *
	 * @param int $flag The flag to set
	 * @return self Returns `$this` for method chaining
	 */
	public function setFlag($flag)
	{
	    $this->flag = $flag;
		return $this;
	}

	/**
	 * Get the reporter flag
	 *
	 * @return int
	 */
	public function getFlag()
	{
	    return $this->flag;
	}

	/**
	 * Set the adapter type to use
	 *
	 * @param string $type The type name
	 * @return self $this for method chaining
	 * @throws Throws a RuntimeException if the adapter doesn't exist
	 */
	public function setAdapterType($type)
	{
		$adapter_type = '\Library\ReporterAdapter\\'.ucfirst($type);
        if (class_exists($adapter_type)) {
            $this->setAdapter(new $adapter_type);
        } else {
            throw new \RuntimeException(
                sprintf('Reporter adapter for type "%s" doesn\'t exist!', $adapter_type)
            );
        }
		return $this;
	}

	/**
	 * Get the current adapter name
	 *
	 * @return object
	 */
	public function getAdapterType()
	{
		return !empty($this->__adapter) ? get_class($this->__adapter) : null;
	}

	/**
	 * Set the adapter
	 *
	 * @param object $adapter The instance of a ReporterAdapter extending `Library\AbstractReporterAdapter`
	 * @return self $this for method chaining
	 */
	public function setAdapter(AbstractReporterAdapter $adapter)
	{
	    $cls = get_class($adapter);
		foreach(self::$default_masks as $_mask) {
		    if (null===@constant($cls.'::mask_'.$_mask)) {
                throw new \LogicException(
                    sprintf('Reporter adapter "%s" must define a mask named "%s"!', $cls, $_mask)
                );
		    }
		}
		$this->__adapter = $adapter;
		return $this;
	}

	/**
	 * Get the current adapter
	 *
	 * @return object
	 */
	public function getAdapter()
	{
		return $this->__adapter;
	}

	/**
	 * Set some content
	 *
	 * @param string $output The content string
	 * @return self Returns `$this` for method chaining
	 */
	public function setOutput($output)
	{
	    if ($this->getFlag() & self::OUTPUT_APPEND) {
    		$this->output .= $output;
    	} elseif ($this->getFlag() & self::OUTPUT_BY_LINE) {
    		$this->output = $output;
    	}
		return $this;
	}

	/**
	 * Get the processed content
	 *
	 * @return string The content string
	 */
	public function getOutput()
	{
		return $this->output;
	}

// -------------------------
// Rendering
// -------------------------

	/**
	 * Render a content with a specific tag mask
	 *
	 * @param string $content The content string to use
	 * @param string $tag_type The type of tag mask to use
	 * @param string|array $args An array of arguments to pass to the mask (or a single string that 
	 *                          will be taken as the first array item)
	 * @return string|self Returns the line of output if the object flag is setted on `OUTPUT_BY_LINE`,
	 *                      or `$this` if the flag is setted on `OUTPUT_APPEND`
	 */
	public function render($content, $tag_type = 'default', $args = null)
	{
	    if (is_null($args)) $args = array();
	    if (!is_array($args)) $args = array( $args );
	    $output = $this->getAdapter()->renderTag($content, $tag_type, $args);
	    $this->setOutput($output);
	    if ($this->getFlag() & self::OUTPUT_APPEND) {
    	    return $this;
    	} elseif ($this->getFlag() & self::OUTPUT_BY_LINE) {
    	    return $output;
    	}
	}

	/**
	 * Display on screen a content with a specific tag mask
	 *
	 * @param string $content The content string to use
	 * @param string $tag_type The type of tag mask to use
	 * @param string|array $args An array of arguments to pass to the mask (or a single string that 
	 *                          will be taken as the first array item)
	 * @return void
	 */
	public function write($content, $tag_type = 'default', $args = null)
	{
	    if (is_null($args)) $args = array();
	    if (!is_array($args)) $args = array( $args );
	    $output = $this->getAdapter()->renderTag($content, $tag_type, $args);
	    echo PHP_EOL.$output.PHP_EOL;
	}

	/**
	 * Render a content with a specific tag mask and some placeholders
	 *
	 * This is quite the same as the `render()` method but in this case, the `$content` string
	 * may contains some placeholders like `@name@` that will be replaced in the result by
	 * the `name` item of the `$multi` array argument after rendering it by the `render()` method.
	 *
	 * For instance:
	 *
	 *     $str = $obj->renderMulti( 'my string with @name@ placeholder', 'default', array(
	 *         'name' => array( 'a specific string as' , 'strong' )
	 *     ));
	 *
	 * will return:
	 *
	 *     "<p>my string with <strong>a specific string as</strong> placeholder</p>"
	 *
	 * @param string $content The content string to use
	 * @param string $tag_type The type of tag mask to use
	 * @param array $multi The array of imbricated elements for content replacements
	 * @param string|array $args An array of arguments to pass to the mask (or a single string that 
	 *                          will be taken as the first array item)
	 * @param string $placeholder_mask The mask used to build placeholders names in `$content`
	 * @return string|self Returns the line of output if the object flag is setted on `OUTPUT_BY_LINE`,
	 *                      or `$this` if the flag is setted on `OUTPUT_APPEND`
	 */
	public function renderMulti($content, $tag_type = 'default', array $multi = array(), $args = null, $placeholder_mask = '@%s@')
	{
	    if (is_null($args)) $args = array();
	    if (!is_array($args)) $args = array( $args );

        // rendering all placeholders
        $placeholders_table = array();
        foreach($multi as $item=>$item_args) {
            $placeholders_table[$item] = call_user_func_array(
                array($this->getAdapter(), 'renderTag'),
                $item_args
            );
        }

        // replacing placeholders
        $full_content = $content;
        foreach ($placeholders_table as $name => $value) {
            $full_content = strtr($full_content, array(sprintf($placeholder_mask, $name) => $value));
        }

        // rendering final output
	    $output = $this->getAdapter()->renderTag($full_content, $tag_type, $args);

	    $this->setOutput($output);
	    if ($this->getFlag() & self::OUTPUT_APPEND) {
    	    return $this;
    	} elseif ($this->getFlag() & self::OUTPUT_BY_LINE) {
    	    return $output;
    	}
	}

}

// Endfile