<?php
/**
 * This file is part of the Library package.
 *
 * Copyright (c) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
 * @author      Piero Wbmstr <me@e-piwi.fr>
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

// Endfile
