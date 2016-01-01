<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
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
 * File helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Filesystem as FilesystemHelper;
 *
 * @author  piwi <me@e-piwi.fr>
 */
class Filesystem
{

    /**
     * Returns a relative path between two filesystem realpaths
     *
     * @param   string  $from   The absolute path to work from
     * @param   string  $to     The absolute path to resolve from `$from`
     * @return  string  The relative path from `$from` to `$to`
     */
    public static function resolveRelatedPath($from, $to)
    {
        $from_parts = array_filter( explode('/', $from) );
        $to_parts = array_filter( explode('/', $to) );
        foreach($from_parts as $i=>$path) {
            if (in_array($path, $to_parts)) {
                $from_parts[$i] = null;
                $to_parts[$i] = null;
            }
        }
        $from_parts = array_filter($from_parts);
        $to_parts = array_filter($to_parts);
        for($i=0; $i<count($from_parts); $i++) {
            array_unshift($to_parts, '..');
        }
/*
        foreach($from_parts as $path) {
            array_unshift($to_parts, $path);
        }
*/
        return join('/', $to_parts);
    }

    /**
     * Get safely the octal form of `$int` if necessary
     *
     * @param   int     $int
     * @return  int
     */
    public static function getOctal($int)
    {
        return (decoct(octdec($int))===$int) ? $int : octdec($int);
    }

}

