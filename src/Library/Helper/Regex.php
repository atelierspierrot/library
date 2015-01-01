<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (ↄ) 2013-2015 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
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
 */

namespace Library\Helper;

/**
 * Regex helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Regex as RegexHelper;
 *
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class Regex
{

    /**
     * Get a read-to-use regular expression from a string pattern
     *
     * @param   string  $string     The string to construct the expression
     * @param   string  $delimiter  The delimiter to use for the expression (default is `#`)
     * @param   string  $options    The options to use for the expression (default is `i`)
     * @param   bool    $strict     Strictly match the string (default is `false`)
     * @return  string
     */
    public static function getPattern($string = '', $delimiter = '#', $options = 'i', $strict = false)
    {
        $replacements = array(
            '.'=>'\\.',
            '*'=>'.*',
            $delimiter=>'\\'.$delimiter
        );
        return $delimiter.'^'
            .($strict ? '' : '.*')
            .strtr($string, $replacements)
            .($strict ? '' : '.*')
            .'$'.$delimiter.$options;
    }

}

// Endfile
