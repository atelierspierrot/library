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
 * File helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Filesystem as FilesystemHelper;
 *
 * @author      Piero Wbmstr <me@e-piwi.fr>
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

// Endfile
