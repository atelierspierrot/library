<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library;

use \Library\Helper\Html as HtmlHelper;

/**
 * Reporter Adapters interface
 *
 * All Reporter adapters must extend this abstract class and defines its abstract methods
 *
 * Each reporter MUST define all entries of the `Library\Reporter::$default_masks` array
 * as a class constant:
 *
 *     	const mask_XXX = "<tag>%s</tag>";
 *
 * @author 		Piero Wbmstr <me@e-piwi.fr>
 */
abstract class AbstractReporterAdapter
{

	/**
	 * Adapter new line sign
	 *
	 * @return string
	 */
	public function newLine()
	{
		return constant(get_class($this).'::mask_new_line');
	}

	/**
	 * Adapter new tabulation
	 *
	 * @return string
	 */
	public function tab()
	{
		return constant(get_class($this).'::mask_tab');
	}

	/**
	 * Tag strings constructor (it replaces arguments as @arg@ by their value)
	 *
	 * @param string $str The string to compose
	 * @param string $tag_type The tag name to get the class mask constant
	 * @param array $args The arguments as 'id' => 'remplacement'
	 * @return string
	 * @throws Exception Throws an Exception if the tag mask doesn't exist in the adapter
	 */
	protected function _tagComposer($str = '', $tag_type = 'default', array $args = array())
	{
	    $const_name = 'mask_'.$tag_type;
	    $mask = @constant(get_class($this).'::'.$const_name);
	    if (null===$mask) {
	        throw new \Exception(
	            sprintf('Unknown mask for tag type "%s" in "%s" Reporter adapter!', $tag_type, get_class($this))
	        );
	    }

	    if (isset($args['attrs'])) {
	        $args['attributes'] = $args['attrs'];
	        unset($args['attrs']);
	    }
	    if (isset($args['attributes'])) {
	        array_push($args, HtmlHelper::parseAttributes($args['attributes']));
	        unset($args['attributes']);
	    }

		if (strlen($str)) {
		    array_unshift($args, $str);
			$str = vsprintf($mask, array_pad($args, substr_count($mask, '%'), ''));
		}
		return $str;
	}

	/**
	 * Render a content with a specific tag mask
	 *
	 * The `$tag_type` may be one of the `Library\Reporter::$default_tag_types` array.
	 *
	 * @param string $content The content string to use
	 * @param string $tag_type The type of tag mask to use
	 * @return string Must return the content string built
	 */
	abstract public function renderTag($content, $tag_type = 'default');

}

// Endfile