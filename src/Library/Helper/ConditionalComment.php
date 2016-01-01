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

namespace Library\Helper;

/**
 * Internet Explorer conditional HTML comment
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\ConditionalComment as ConditionalCommentHelper;
 *     // or
 *     use Library\Helper\ConditionalComment as CCHelper;
 *
 * @author  piwi <me@e-piwi.fr>
 */
class ConditionalComment
{

    /**
     * Shortcuts to use in conditions
     * @static array
     */
    public static $condition_shortcuts = array(
        '<='    => 'lte',
        '>='    => 'gte',
        '<'     => 'lt',
        '>'     => 'gt',
    );

    /**
     * Operators to use in conditions
     * @static array
     */
    public static $operator_shortcuts = array(
        'OR'    => '|',
        'AND'   => '&',
        'NOT'   => '!',
    );

    /**
     * @static string
     */
    public static $conditional_item = '(%s)';

    /**
     * @static string
     */
    public static $internet_explorer = 'IE';

    /**
     * Build an HTML condition string for Internet Explorer around content
     *
     * Condition can be an array of conditions, that will be related with the operator.
     *
     * Each condition can be written like ">=5" for instance, to define a condition
     * for IE "greater than or equal to" version 5.
     *
     * @param string $content
     * @param string|array $condition(s)
     * @param string $operator Can be 'OR' (default) or 'AND'
     * @param bool $global May the content be also defined globally
     *
     * @return string
     */
    public static function buildCondition($content, $condition = 'if IE', $operator = 'OR', $global = false)
    {
        if (empty($condition)) {
            return $content;
        }
        $condition_str = '';
        if (is_array($condition)) {
            $count=0;
            foreach ($condition as $_cond) {
                if ($count===0) {
                    $condition_str .= sprintf(self::$conditional_item, 
                        self::parseSingleCondition($_cond));
                } else {
                    $condition_str .= self::parseSingleCondition($operator.$_cond);
                }
                $count++;
            }
        } else {
            $condition_str = self::parseSingleCondition($condition);
        }
        return self::writeCondition($content, 'if ' . $condition_str, $global);
    }

    /**
     * Write an HTML condition for Internet Explorer around content
     *
     * @param string $content
     * @param string $condition
     * @param bool $global May the content be also defined globally
     *
     * @return string
     */
    public static function writeCondition($content, $condition = null, $global = false)
    {
        if (empty($condition)) {
            return $content;
        }
        return '<!--[' . $condition . ']>'
            . ($global ? '<!-->' : '')
            . ' ' . $content . ' '
            . ($global ? '<!--' : '')
            . '<![endif]-->';
    }

    /**
     * Parse a single condition item replacing shortcuts
     *
     * To build a final multi-items condition string, pass to this function a string like
     * "AND ..." which will render "&(...)".
     * To build a NOT condition string, pass to this function a string like
     * "NOT ..." which will render "!(...)".
     *
     * @param string $condition
     *
     * @return string
     */
    public static function parseSingleCondition($condition)
    {
        $add_ie = strpos($condition, self::$internet_explorer)===false;
        foreach (self::$condition_shortcuts as $key=>$val) {
            if (strpos($condition, $key)!==false) {
                $condition = str_replace(
                    $key,
                    $val . ' ' . ($add_ie ? self::$internet_explorer . ' ' : ''),
                    $condition
                );
                $add_ie = false;
            } elseif (strpos($condition, $val)!==false && $add_ie) {
                $condition = str_replace(
                    $val,
                    $val . ' ' . self::$internet_explorer . ' ',
                    $condition
                );
                $add_ie = false;
            }
        }        
        foreach (self::$operator_shortcuts as $key=>$val) {
            if (strpos($condition, $key)!==false) {
                $condition_item = str_replace($key, '', $condition);
                $condition = sprintf($val . self::$conditional_item, $condition_item);
            }
        }
        if ($add_ie) {
            $condition = self::$internet_explorer . ' ' . $condition;
        }
        return $condition;
    }

}

