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
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Directory as DirectoryHelper;
 *
 * @author      Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Directory
{

    /**
     * Get a dirname with one and only trailing slash
     *
     * @param string $dirname
     * @return string
     */
    public static function slashDirname($dirname = null)
    {
        if (is_null($dirname) || empty($dirname)) return '';
        return rtrim($dirname, '/ '.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    public static function isGitClone($path = null)
    {
        if (is_null($dirname) || empty($dirname)) return false;
        $dir_path = self::slashDirname($path).'.git';
        return (bool) file_exists($dir_path) && is_dir($dir_path);
    }

    public static function isDotPath($path = null)
    {
        if (is_null($dirname) || empty($dirname)) return false;
        return (bool) '.'===substr(basename($path), 0, 1);
    }

// ------------------------
// Manipulation
// ------------------------

    /**
     * Build a directory with its whole hierarchy if necessary
     *
     * @param string $path
     * @param int $mode
     * @param bool $recursive
     * @return bool
     */
    public static function ensureExists($path, $mode = 777, $recursive = true)
    {
        if (file_exists($path) && is_dir($path)) return true;
        return self::create($path, $mode, $recursive);
    }

    /**
     * Create a directory if necessary
     *
     * @param string $path
     * @param int $mode
     * @param bool $recursive
     * @return bool
     */
    public static function create($path, $mode = 777, $recursive = true)
    {
        return mkdir($path, Filesystem::getOctal($mode), $recursive);
    }

    /**
     * Build a directory with its whole hierarchy if necessary
     *
     * @param string $path
     * @param int $mode
     * @param bool $recursive
     * @param array $logs Logs registry passed by reference
     * @return bool
     */
    public static function remove($path, array &$logs = array())
    {
        if (file_exists($path) && is_dir($path)) {
            if (!is_dir($path) || is_link($path)) {
                if (unlink($path)) {
                    return true;
                } else {
                    $logs[] = sprintf('Can not unlink file "%s".', $path);
                }
            }
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path), 
                \RecursiveIteratorIterator::SELF_FIRST | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS
            );
            foreach($iterator as $item) {
                if ($item->isDir()) {
                    if (false===$ok = self::remove($item, $logs)) {
                        $logs[] = sprintf('Can not remove diretory "%s".', $item);
                    }
                } else {
                    if (false===$ok = File::remove($item, $logs)) {
                        $logs[] = sprintf('Can not unlink file "%s".', $item);
                    }
                }
            } 
            if (true===$ok) {
                if (rmdir($path)) {
                    return true;
                } else {
                    $logs[] = sprintf('Can not remove directory "%s".', $path);
                }
            }
            clearstatcache();
            return $ok;
        } else {
            $logs[] = sprintf('Directory "%s" not found.', $path);
        }
        return false;
    }

    /**
     * Change rights on a directory
     *
     * @param string $path
     * @param int $mode
     * @param bool $recursive
     * @param int $file_mode
     * @param array $logs Logs registry passed by reference
     * @return bool
     */
    public static function chmod($path, $mode = 777, $recursive = true, $file_mode = 777, array &$logs = array())
    {
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
