<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library\Helper;

use \Library\Helper\Text as TextHelper;
 
/**
 * File helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\File as FileHelper;
 *
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class File
{

    /**
     * Returns a filename or directory that does not exist in the destination
     *
     * @param   string  $filename       The name of the file or folder you want to create
     * @param   string  $dir            The destination directory
     * @param   boolean $force_file     Should we force the creation of a file, adding an extension? (TRUE by default)
     * @param   string  $extension      The extension to add in the case of a file
     * @return  string  The filename or directory, with a possible addition to be sure that it does not exist in the destination directory
     */
    public static function getUniqFilename($filename = '', $dir = null, $force_file = true, $extension = 'txt')
    {
        $extension = trim($extension, '.');

        if (empty($filename)){
            $filename = uniqid();
            if ($force_file) $filename .= '.'.$extension;
        }
        if (is_null($dir)) 
            $dir = defined('_DIR_TMP') ? _DIR_TMP : '/tmp';
        $dir = Directory::slashDirname($dir);
        $_ext = self::getExtension($filename, true);
        $_fname = str_replace($_ext, '', $filename);
        $i = 0;
        while (file_exists($dir.$_fname.$_ext)) {
            $_fname .= ($i==0 ? '_' : '').rand(10, 99);
            $i++;
        }
        return $_fname.$_ext;
    }

    /**
     * Formatting file names
     *
     * @param   string  $filename   The filename to format
     * @param   boolean $lowercase  Should we return the name un lowercase (FALSE by default)
     * @param   string  $delimiter  The delimiter used for special chars substitution
     * @return  string  A filename valid on (almost) all systems
     */
    public static function formatFilename($filename = '', $lowercase = false, $delimiter = '-')
    {
        if (empty($filename)) return '';

        $_ext = self::getExtension($filename, true);
        if ($_ext) $filename = str_replace($_ext, '', $filename);

        $string = TextHelper::stripSpecialChars($filename);

        if ($lowercase) $string = strtolower($string);

        $string = str_replace(" ",$delimiter,$string);
        $string = preg_replace('#\-+#',$delimiter,$string);
        $string = preg_replace('#([-]+)#',$delimiter,$string);
        $string = trim($string,$delimiter);

        if ($_ext) $string .= $_ext;

        return $string;
    }

    /**
     * Returns the extension of a file name
     *
     * It basically returns everything after last dot. No validation is done.
     *
     * @param   string  $file_name  The file_name to work on
     * @param   bool    $dot
     * @return  null|string     The extension if found, `null` otherwise
     */
    public static function getExtension($file_name = '', $dot = false)
    {
        $exploded_file_name = explode('.', $file_name);
        return strpos($file_name, '.') ? ($dot ? '.' : '').end($exploded_file_name) : null;
    }

    /**
     * List of characters replaced by a space in a file name to build a human readable string
     */
    public static $REPLACEMENT_FILENAMES_CHARS  = array('.', '-', '_', '/');

    /**
     * Render a human readable string from a file name
     *
     * The original file name is rebuilt striping the extension
     * and a set of commonly used separator characters in file or directories names.
     *
     * @param   string  $file_name  The file_name to work on
     * @return  string  The resulting human readable file name
     */
    public static function getHumanReadableFilename($file_name = '')
    {
        $ext = self::getExtension($file_name);
        if (!empty($ext)) {
            $file_name = str_replace('.'.$ext, '', $file_name);
        }
        return ucfirst( str_replace(self::$REPLACEMENT_FILENAMES_CHARS, ' ', $file_name) );
    }

    /**
     * List of units to build the size field, ordered by 1024 operator on original size
     */
    public static $FILESIZE_ORDERED_UNITS       = array('o','Ko','Mo','Go','To');

    /**
     * Returns a formatted file size in bytes or derived unit
     *
     * This will return the size received transforming it to be readable, with the appropriate
     * unit chosen in `self::$FILESIZE_ORDERED_UNITS`.
     *
     * @param   float   $size           Refer to the size (in standard format given by the `stat()` function)
     * @param   int     $round          The number of decimal places (default is 3)
     * @param   string  $dec_delimiter  The decimal separator (default is a comma)
     * @return  int
     */
    public static function getTransformedFilesize($size = 0, $round = 3, $dec_delimiter = ',')
    {
        $count=0;
        while($size >= 1024 && $count < (count(self::$FILESIZE_ORDERED_UNITS)-1)) {
            $count++;
            $size /= 1024;
        }
        if ($round>=0) {
            $arr = pow(10, $round);
            $number = round($size * $arr) / $arr;
        } else {
            $number = $size;
        }
        return str_replace('.',$dec_delimiter,$number).' '.self::$FILESIZE_ORDERED_UNITS[$count];
    }

// --------------------
// Manipulation
// --------------------

    /**
     * Create an empty file or touch an existing file
     *
     * @param   string  $file_path
     * @param   array   $logs   Logs registry passed by reference
     * @return  bool
     */
    public static function touch($file_path, array &$logs = array())
    {
        if (!file_exists($file_path)) {
            $target_dir = dirname($file_path);
            $ok = !file_exists($target_dir) ? Directory::create($target_dir) : true;
        } else {
            $ok = true;
        }
        if (touch($file_path)) {
            clearstatcache();
            return true;
        } else {
            $logs[] = sprintf('Can not touch file "%s".', $file_path);
        }
        return false;
    }

    /**
     * Remove a file if it exists
     *
     * @param   string  $file_path
     * @param   array   $logs       Logs registry passed by reference
     * @return  bool
     */
    public static function remove($file_path, array &$logs = array())
    {
        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                clearstatcache();
                return true;
            } else {
                $logs[] = sprintf('Can not unlink file "%s".', $file_path);
            }
        } else {
            $logs[] = sprintf('File path "%s" does not exist (can not be removed).', $file_path);
        }
        return false;
    }

    /**
     * Copy file `$file_path` if it exists to `$target_path`
     *
     * @param   string  $file_path
     * @param   string  $target_path
     * @param   bool    $force
     * @param   array   $logs   Logs registry passed by reference
     * @return  bool
     */
    public static function copy($file_path, $target_path, $force = false, array &$logs = array())
    {
        if (!file_exists($file_path)) {
            $logs[] = sprintf('File path "%s" to copy was not found.', $file_path);
            return false;
        }
        $target_dir = dirname($target_path);
        $ok = (!file_exists($target_dir) && true===$force) ? Directory::create($target_dir) : true;
        if ($ok) {
            if (!file_exists($target_path) || true===$force) {
                if (copy($file_path, $target_path)) {
                    return true;
                } else {
                    $logs[] = sprintf('Can not copy file "%s" to "%s".', $file_path, $target_path);
                }
            } else {
                $logs[] = sprintf('Can not over-write target file "%s" by copy (use param `$force=true`).', $target_path);
            }
        } else {
            $logs[] = sprintf('Can not create target directory "%s" for copy.', $target_dir);
        }
        return false;
    }

    /**
     * Write a content in a file
     *
     * @param   string  $file_path
     * @param   string  $content
     * @param   string  $type
     * @param   bool    $force
     * @param   array   $logs
     * @return  bool
     */
    public static function write($file_path, $content, $type = 'a', $force = false, array &$logs = array())
    {
        if (!file_exists($file_path)) {
            if (true===$force) {
                self::touch($file_path, $logs);
            } else {
                $logs[] = sprintf('File path "%s" to copy was not found.', $file_path);
                return false;
            }
        }
        if (is_writable($file_path)) {
            $h = fopen($file_path, $type);
            if (false !== fwrite($h, $content)) {
                fclose($h);
                return true;
            }
        }
        $logs[] = sprintf('Can not write in file "%s".', $file_path);
        return false;
    }

}

// Endfile
