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
