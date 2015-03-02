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
            '.'         => '\\.',
            '*'         => '.*',
            $delimiter  => '\\'.$delimiter
        );
        return $delimiter.'^'
            .($strict ? '' : '.*')
            .strtr($string, $replacements)
            .($strict ? '' : '.*')
            .'$'.$delimiter.$options;
    }

}

// Endfile
