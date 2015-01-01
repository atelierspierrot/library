<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
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
 * Directory helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Directory as DirectoryHelper;
 *
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class Directory
{

    /**
     * @var int
     */
    const DEFAULT_UNIX_CHMOD_DIRECTORIES = 755;

    /**
     * @var int
     */
    const DEFAULT_UNIX_CHMOD_FILES = 644;

    /**
     * Get a dirname with one and only trailing slash
     *
     * @param   string  $dirname
     * @return  string
     */
    public static function slashDirname($dirname = null)
    {
        if (is_null($dirname) || empty($dirname)) return '';
        return rtrim($dirname, '/ '.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    /**
     * Test if a path seems to be a git clone
     *
     * @param   string  $path
     * @return  bool
     */
    public static function isGitClone($path = null)
    {
        if (is_null($path) || empty($path)) return false;
        $dir_path = self::slashDirname($path).'.git';
        return file_exists($dir_path) && is_dir($dir_path);
    }

    /**
     * Test if a filename seems to have a dot as first character
     *
     * @param   string  $path
     * @return  bool
     */
    public static function isDotPath($path = null)
    {
        if (is_null($path) || empty($path)) return false;
        return '.'===substr(basename($path), 0, 1);
    }

// ------------------------
// Manipulation
// ------------------------

    /**
     * Build a directory with its whole hierarchy if necessary
     *
     * @param   string  $path
     * @param   int     $mode
     * @param   bool    $recursive
     * @return  bool
     */
    public static function ensureExists($path, $mode = self::DEFAULT_UNIX_CHMOD_DIRECTORIES, $recursive = true)
    {
        if (file_exists($path) && is_dir($path)) return true;
        return self::create($path, $mode, $recursive);
    }

    /**
     * Create a directory if necessary
     *
     * @param   string  $path
     * @param   int     $mode
     * @param   bool    $recursive
     * @return  bool
     */
    public static function create($path, $mode = self::DEFAULT_UNIX_CHMOD_DIRECTORIES, $recursive = true)
    {
        return mkdir($path, Filesystem::getOctal($mode), $recursive);
    }

    /**
     * Remove a directory with its whole contents
     *
     * @param   string  $path
     * @param   array   $logs   Logs registry passed by reference
     * @return  bool
     */
    public static function remove($path, array &$logs = array())
    {
        if (file_exists($path) && is_dir($path)) {
            if (!is_dir($path) || is_link($path)) {
                if (array_key_exists($path, $logs)) {
                    return false;
                }
                if (unlink($path)) {
                    return true;
                } else {
                    $logs[$path] = sprintf('Can not unlink file "%s".', $path);
                }
            }
            $ok = self::purge($path, $logs);
            if (true===$ok) {
                if (array_key_exists($path, $logs)) {
                    return false;
                }
                if (rmdir($path)) {
                    return true;
                } else {
                    $logs[$path] = sprintf('Can not remove directory "%s".', $path);
                }
            }
            clearstatcache();
            return $ok;
        } else {
            $logs[$path] = sprintf('Directory "%s" not found.', $path);
        }
        return false;
    }

    /**
     * Remove a directory contents but not the directory itself
     *
     * @param   string  $path
     * @param   array   $logs   Logs registry passed by reference
     * @return  bool
     */
    public static function purge($path, array &$logs = array())
    {
        if (file_exists($path) && is_dir($path)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path), 
                \RecursiveIteratorIterator::SELF_FIRST | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS
            );
            foreach ($iterator as $item) {
                if (in_array($item->getFilename(), array('.', '..'))) {
                    continue;
                }
                $_path = $item->getRealpath();
                if (array_key_exists($_path, $logs)) {
                    return false;
                }
                if ($item->isDir()) {
                    if (false===$ok = self::remove($_path, $logs)) {
                        $logs[$_path] = sprintf('Can not remove diretory "%s".', $_path);
                    }
                } else {
                    if (false===$ok = File::remove($_path, $logs)) {
                        $logs[$_path] = sprintf('Can not unlink file "%s".', $_path);
                    }
                }
            } 
            clearstatcache();
            return $ok;
        } else {
            $logs[$path] = sprintf('Directory "%s" not found.', $path);
        }
        return false;
    }

    /**
     * Change rights on a directory
     *
     * @param   string  $path
     * @param   int     $mode
     * @param   bool    $recursive
     * @param   int     $file_mode
     * @param   array   $logs       Logs registry passed by reference
     * @return  bool
     */
    public static function chmod(
        $path, $mode = self::DEFAULT_UNIX_CHMOD_DIRECTORIES,
        $recursive = true, $file_mode = self::DEFAULT_UNIX_CHMOD_FILES, array &$logs = array()
    ){
        $ok = false;
        if (file_exists($path) && is_dir($path)) {
            if (true!==$ok = chmod($path, Filesystem::getOctal($mode))) {
                $logs[] = sprintf('Can not change mode on directory "%s" (trying to set them on "%d").', $path, $mode);
            }
            if ($ok && true===$recursive) {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path), 
                    \RecursiveIteratorIterator::SELF_FIRST | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS
                );
                foreach($iterator as $item) {
                    if (in_array($item->getFilename(), array('.', '..'))) {
                        continue;
                    }
                    if ($item->isDir()) {
                        if (true!==$ok = chmod($item, Filesystem::getOctal($mode))) {
                            $logs[] = sprintf('Can not change mode on sub-directory "%s" (trying to set them on "%d").', $item, $mode);
                        }
                    } elseif ($item->isFile() && !$item->isLink()) {
                        if (true!==$ok = chmod($item, Filesystem::getOctal($file_mode))) {
                            $logs[] = sprintf('Can not change mode on file "%s" (trying to set them on "%d").', $item, $file_mode);
                        }
                    }
                } 
            }
            clearstatcache();
        } else {
            $logs[] = sprintf('Directory "%s" not found (can not change mode).', $path);
        }
        return $ok;
    }

}

// Endfile
