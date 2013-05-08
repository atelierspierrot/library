<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\ReporterAdapter;

use Library\AbstractReporterAdapter;

use Library\Tool\Table as TableTool;

/**
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Html
    extends AbstractReporterAdapter
{

// ----------------------------------
// Masks
// ----------------------------------

	const mask_default = '%s';
	const mask_new_line = '<br />';
	const mask_tab = '&nbsp;';
	const mask_key_value = '<strong>%1$s</strong>: %2$s';
	const mask_unordered_list = '<ul %2$s>%1$s</ul>';
	const mask_unordered_list_item = '<li %2$s>%1$s</li>';
	const mask_ordered_list = '<ol %2$s>%1$s</ol>';
	const mask_ordered_list_item = '<li %2$s>%1$s</li>';
	const mask_table = '<table %2$s>%1$s</table>';
	const mask_table_title = '<caption>%1$s</caption>';
	const mask_table_head = '<thead>%1$s</thead>';
	const mask_table_head_line = '<tr %2$s>%1$s</tr>';
	const mask_table_head_cell = '<th %2$s>%1$s</th>';
	const mask_table_body = '<tbody>%1$s</tbody>';
	const mask_table_body_line = '<tr %2$s>%1$s</tr>';
	const mask_table_body_cell = '<td %2$s>%1$s</td>';
	const mask_table_foot = '<tfoot>%1$s</tfoot>';
	const mask_table_foot_line = '<tr %2$s>%1$s</tr>';
	const mask_table_foot_cell = '<td %2$s>%1$s</td>';
	const mask_definition = '<dl %2$s>%1$s</dl>';
	const mask_definition_term = '<dt %2$s>%1$s</dt>';
	const mask_definition_description = '<dd %2$s>%1$s</dd>';
	const mask_code = '<code %2$s>%1$s</code>';
	const mask_pre_formated = "<pre %2\$s>\n%1\$s\n</pre>";
	const mask_title = '<h%2$d %3$s>%1$s</h%2$d>';
	const mask_paragraph = '<p %2$s>%1$s</p>';
	const mask_citation = '<blockquote %2$s>%1$s</blockquote>';
	const mask_bold = '<strong %2$s>%1$s</strong>';
	const mask_italic = '<em %2$s>%1$s</em>';
	const mask_link = '<a href="%1$s" title="See online %1$s" %2$s>%1$.20s</a>';

// ----------------------------------
// AbstractReporterAdapter
// ----------------------------------

    /**
     * Array of a table parts
     * @var array
     */
    public static $table_scopes = array( 'head', 'body', 'foot' );

	/**
	 * Render a content with a specific tag mask
	 *
	 * The `$tag_type` may be one of the `Library\Reporter::$default_tag_types` array.
	 *
	 * @param array|string $content The content string to use or an array of strings (for lists for instance)
	 * @param string $tag_type The type of tag mask to use
	 * @param array $args An array of arguments to pass to the mask
	 * @return string Must return the content string built
	 */
	public function renderTag($content, $tag_type = 'default', array $args = array())
	{
	    switch($tag_type) {

	        // case of the tables ($content is an array of lines that are an array of cells)
	        case 'table':
	            if (!is_array($content)) $content = array( $content );
	            // cleaning the contents
	            $correspondances = array(
	                'thead'=>'head', 'tbody'=>'body', 'tfoot'=>'foot'
	            );
	            foreach($correspondances as $var=>$val) {
    	            if (isset($content[$var])) {
    	                $content[$val] = $content[$var];
    	                unset($content[$var]);
    	            }
	            }
	            if (!isset($content['body'])) $content = array( 'body'=>$content );
	            $this->_doTable($content, $args);
	            break;
	        
	        // case of the lists ($content is an array of items)
	        case 'list': case 'unordered_list': case 'ordered_list':
	            if ('list'===$tag_type) $tag_type = 'unordered_list';
	            if (!is_array($content)) $content = array( $content );
	            $this->_doList($content, $args, $tag_type);
	            break;

	        // case of the definitions lists ($content is an array of items liek term=>def)
	        case 'def': case 'definition': case 'definitions':
	            if (!is_array($content)) {
	                $tag_type = 'default';
	            } else {
	                $this->_doDefinitions($content, $args);
	            }
	            break;

	        // case of the titles (if no argument, will render a h1)
	        case 'title':
	            if (empty($args)) $args = array(1);
	            break;

	        default: break;
	    }
	    return $this->_tagComposer($content, $tag_type, $args);
	}

    /**
     * Find a specific entry in arguments and unset it
     *
	 * @param array $args An array of arguments to pass to the mask
	 * @param string $scope A scope to search in the arguments array
	 * @param misc $default The default value to send if the scope was not found in `$args`
	 * @param bool $unset Unset the entry if found (default is `true`)
	 * @return misc Returns the found entry in `$args` if so
     */
    protected function _getArgsStack(array &$args = array(), $scope = null, $default = array(), $unset = true)
    {
        $found_args = $default;
        if (isset($args[$scope])) {
            $found_args = $args[$scope];
            if (true===$unset) {
                unset($args[$scope]);
            }
        }
        return $found_args;
    }

    /**
     * Process a list content
     *
	 * To build a list, `$content` may be the array of list items. You can specify a set of
	 * arguments used for all items defining `$args[ items ]` and a specific set of arguments
	 * for each item defining `$args[ itemX ]` where X is the item key (0 based numeric key).
	 *
	 * @param array $content The content array of list items
	 * @param array $args An array of arguments to pass to the mask
	 * @param string $tag_type The type of tag mask to use
	 * @return void Returns nothing as the `$content` and `$args` parameters are passed by reference
     */
    protected function _doList(&$content, array &$args = array(), $tag_type = 'unordered_list')
    {
        $items_content = '';

        // common arguments for all items
        $items_args = $this->_getArgsStack($args, 'items');

        // loop on each list items
        foreach($content as $i=>$item_str) {
            $item_args = array_merge_recursive($items_args, $this->_getArgsStack($args, 'item'.$i));
            $items_content .= $this->_tagComposer($item_str, $tag_type.'_item', $item_args);
        }
        
        $content = $items_content;
    }

    /**
     * Process a definitions list content
     *
	 * @param array $content The content array of definitions items like "term => description"
	 * @param array $args An array of arguments to pass to the mask
	 * @return void Returns nothing as the `$content` and `$args` parameters are passed by reference
     */
    protected function _doDefinitions(&$content, array &$args = array())
    {
        $items_content = '';

        // common arguments for all items
        $terms_args = $this->_getArgsStack($args, 'term');
        $descriptions_args = $this->_getArgsStack($args, 'description');

        // loop on each list items
        foreach($content as $term=>$def) {
            $term_args = array_merge_recursive($terms_args, $this->_getArgsStack($args, 'term'.$i));
            $description_args = array_merge_recursive($descriptions_args, $this->_getArgsStack($args, 'description'.$i));
            $items_content .= 
                $this->_tagComposer($term, 'definition_term', $term_args)
                .$this->_tagComposer($def, 'definition_description', $description_args);
        }
        
        $content = $items_content;
    }

    /**
     * Process a table content
     *
	 * @param array $content The content array of table lines
	 * @param array $args An array of arguments to pass to the mask
	 * @return void Returns nothing as the `$content` and `$args` parameters are passed by reference
     */
    protected function _doTable(&$content, array &$args = array())
    {
        $table = new TableTool(
            isset($content['body']) && is_array($content['body']) ? $content['body'] : array($content),
            isset($content['head']) && is_array($content['head']) ? $content['head'] : array(),
            isset($content['foot']) && is_array($content['foot']) ? $content['foot'] : array()
        );
        $table_stacks = $table->getTable();

        // each line building
        $table_content = '';
        foreach(self::$table_scopes as $scope) {
            $scope_lines = '';
            if (isset($table_stacks[$scope]) && is_array($table_stacks[$scope]) && !empty($table_stacks[$scope])) {
                $my_line = '';
                foreach($table_stacks[$scope] as $line) {
                    $this->_doTableLine($line, $args, $scope);
                    $my_line .= $line;
                }
                $scope_lines .= $my_line;
            }
            $scope_args = $this->_getArgsStack($args, $scope);
            $scope_tag = 'table_'.$scope;
            $table_content .= $this->_tagComposer($scope_lines, $scope_tag, $scope_args);
        }
        
        // caption
        if (isset($table_stacks['title'])) {
            $caption_args = $this->_getArgsStack($args, 'title');
            $caption_tag = 'table_title';
            $caption = $this->_tagComposer($table_stacks['title'], $caption_tag, $caption_args);
            $table_content = $caption.$table_content;
        }

        $this->_purgeArgsStackForTable($args, 'cell');
        $this->_purgeArgsStackForTable($args, 'line');

        $content = $table_content;
    }

    /**
     * Process a table line
     *
	 * @param array $content The content array of the table line (cells)
	 * @param array $args An array of arguments to pass to the mask
	 * @return void Returns nothing as the `$content` and `$args` parameters are passed by reference
     */
    protected function _doTableLine(&$content, array &$args = array(), $scope = 'body')
    {
        $my_line = '';
        $cell_args = $this->_getArgsStackForTable($args, 'cell', $scope);
        $cell_tag = 'table_'.$scope.'_cell';
        foreach($content as $cell) {
            $my_line .= $this->_tagComposer($cell, $cell_tag, $cell_args);
        }
        $line_args = $this->_getArgsStackForTable($args, 'line', $scope);
        $line_tag = 'table_'.$scope.'_line';
        $content = $this->_tagComposer($my_line, $line_tag, $line_args);
    }

    /**
     * Fallback system to find a specific entry in arguments for table scopes
     *
     * If the scope entry is defined, it will be returned and unset ; if no scope entry at
     * all is defined, the whole entry will be considered as default for all scopre and will
     * be returned.
     *
	 * @param array $args An array of arguments to pass to the mask
	 * @param string $entry The entry of the args to search in
	 * @param string $scope A scope to search in the arguments array
	 * @return misc Returns the found entry in `$args` if so
     */
    protected function _getArgsStackForTable(array &$args, $entry, $scope)
    {
        $found_args = array();
        $global_args = $this->_getArgsStack($args, $entry, array(), false);
        if (!empty($global_args)) {
            if (array_key_exists($scope, $global_args)) {
                $found_args = $this->_getArgsStack($args[$entry], $scope, array(), false);
            } else {
                $scope_defined = false;
                foreach(self::$table_scopes as $_scope) {
                    if (array_key_exists($_scope, $global_args)) {
                        $scope_defined = true;
                    }
                }
                if (false===$scope_defined) {
                    $found_args = $this->_getArgsStack($args, $entry, array(), false);
                }
            }
        }
        return $found_args;
    }

    /**
     * Clean the arguments array at the end of the table build
     *
	 * @param array $args An array of arguments to pass to the mask
	 * @param string $entry The entry of the args to search in
	 * @return void Returns nothing as the `$args` parameter is passed by reference
     */
    protected function _purgeArgsStackForTable(array &$args, $entry)
    {
        $this->_getArgsStack($args, $entry);
    }

}

// Endfile