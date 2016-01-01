<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
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
 *
 * The source code of this package is available online at
 * <http://github.com/atelierspierrot/library>.
 */

namespace Library\Reporter;

use \Library\Helper\Html as HtmlHelper;

/**
 * Reporter Adapters interface
 *
 * All Reporter adapters must extend this abstract class and defines its abstract methods
 *
 * Each reporter MUST define all entries of the `\Library\Reporter\Reporter::$default_masks` array
 * as a class constant:
 *
 *      const mask_XXX = "<tag>%s</tag>";
 *
 * @author  piwi <me@e-piwi.fr>
 */
abstract class AbstractAdapter
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
     * @param array $args The arguments as 'id' => 'replacement'
     * @return string
     * @throws \Exception if the tag mask doesn't exist in the adapter
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
     * The `$tag_type` may be one of the `\Library\Reporter\Reporter::$default_tag_types` array.
     *
     * @param string $content The content string to use
     * @param string $tag_type The type of tag mask to use
     * @return string Must return the content string built
     */
    abstract public function renderTag($content, $tag_type = 'default');

}

