<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
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
