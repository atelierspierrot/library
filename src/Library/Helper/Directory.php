<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library\Helper;

/**
 * Directory helper
 *
 * As for all helpers, all methods are statics.
 *
 * @author      Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Directory
{

    public static function slashDirname($dirname)
    {
        return rtrim($dirname, '/ '.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    public static function isGitClone($path)
    {
        $dir_path = self::slashDirname($path).'.git';
        return (bool) file_exists($dir_path) && is_dir($dir_path);
    }

    public static function isDotPath($path)
    {
        return (bool) '.'===substr(basename($path), 0, 1);
    }

}

// Endfile
