<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
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

namespace Library;

/**
 * Rotate system for files
 *
 * @author  Piero Wbmstr <me@e-piwi.fr>
 */
class FileRotator
{

    /**
     * @var string
     */
    protected $file_path;

    /**
     * @var int
     */
    protected $file_mtime;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var int
     */
    protected $flag;

    /**
     * @var array
     */
    protected static $config = array(
        'max_filesize' => 1000,             // in octets
        'period_duration' => 86400,         // in seconds (here 1 day)
        'filename_mask' => '%s.@date@',     // mask used for rotation filenames
        'filename_date_tag' => '@date@',    // will be replaced by current date formated with 'date_format' in 'filename_mask'
        'filename_iterator_tag' => '@i@',   // will be replaced by rotation iterator in 'filename_mask'
        'date_format' => 'ymd',             // date format used for the `filename_date_tag`
        'backup_time' => 10,                // max number of backuped files
    );

    const ROTATE_PERIODIC = 1;
    const ROTATE_FILESIZE = 2;

    /**
     * Creation of a new logger entry
     *
     * @param string $file_path Full path of concerned file
     * @param int $flag One of the class `ROTATE_` constants
     * @param array $user_options A set of one shot options
     * @throws \InvalidArgumentException if no `$file_path` argument
     */
    public function __construct($file_path, $flag = self::ROTATE_PERIODIC, array $user_options = array())
    {
        if (!empty($file_path)) {
            $this->file_path = $file_path;
            if (file_exists($this->file_path)) {
                $this->file_mtime = filemtime($this->file_path);
            }
        } else {
            throw new \InvalidArgumentException(
                sprintf('%s class needs a non-empty file path as 1st argument!', __CLASS__)
            );
        }
        $this->flag = !empty($flag) ? $flag : self::ROTATE_PERIODIC;
        $this->options = array_merge(self::$config, $user_options);
    }

    /**
     * Write a string in the file
     *
     * @param string $content The content to add in the file
     * @return bool
     * @throws \RuntimeException if an error occurred trying to rotate or write in file
     */
    public function write($content = '')
    {
        if ($this->rotate()) {
            if (empty($this->file_mtime) && file_exists($this->file_path)) {
                $this->file_mtime = filemtime($this->file_path);
            }
            $f = @fopen($this->file_path, "ab");
            if ($f) {
                fputs($f, str_replace('<', '&lt;', $content));
                fclose($f);
                $this->file_mtime = filemtime($this->file_path);
                return true;
            } else {
                throw new \RuntimeException(
                    sprintf('Can not write in file "%s"!', $this->file_path)
                );
            }
        } else {
            throw new \RuntimeException(
                sprintf('Can not write and/or rotate file "%s"!', $this->file_path)
            );
        }
    }

    /**
     * Rotate file if so
     *
     * @param bool $force Force file rotation, even if `mustRotate()` is `false`
     * @return bool
     */
    public function rotate($force = false)
    {
        $rotator = $this->options['backup_time'];

        if (true===$force || $this->mustRotate()) {
            $dir = dirname($this->file_path);
            $old_file = $this->getFilename(basename($this->file_path), $rotator);
            if (file_exists($dir . '/' . $old_file)) {
                @unlink($dir . '/' . $old_file);
            }
            if (false!==strpos($this->flag, $this->options['filename_iterator_tag'])) {
                while ($rotator-- > 0) {
                    $original_file = $this->getFilename(basename($this->file_path), $rotator);
                    if (file_exists($dir . '/' . $original_file)) {
                        $target_file = $this->getFilename(basename($this->file_path), ($rotator + 1));
                        $ok = @rename($dir . '/' . $original_file, $dir . '/' . $target_file);
                    }
                }
            } else {
                $target_file = $this->getFilename(basename($this->file_path), 1);
                $ok = @rename($this->file_path, $dir . '/' . $target_file);
            }
            return $ok;
        }
        return true;
    }

    /**
     * Is the current file needs to be rotated
     *
     * @return bool
     */
    public function mustRotate()
    {
        if (!file_exists($this->file_path)) return false;

        if ($this->flag & self::ROTATE_FILESIZE) {
            $s = @filesize($this->file_path);
            return (bool) (@is_readable($this->file_path) && $s > $this->options['max_filesize'] * 1024);
        } else {
            if (empty($this->file_mtime)) {
                $this->file_mtime = filemtime($this->file_path);
            }
            $now = date('ymdHis', (time()-$this->options['period_duration']));
            return (bool) (@is_readable($this->file_path) && date('ymdHis', $this->file_mtime) <= $now);
        }
    }

    /**
     * Get the name of a file to rotate
     *
     * @param string $file_name
     * @param int $rotation_index
     * @return string
     */
    public function getFilename($file_name, $rotation_index = 0)
    {
        if ($rotation_index===0) return $file_name;
        $rotation_date = date(
            $this->options['date_format'],
            time() - ($this->options['period_duration'] * $rotation_index)
        );
        return strtr(
            sprintf($this->options['filename_mask'], $file_name),
            array(
                $this->options['filename_date_tag'] => $rotation_date,
                $this->options['filename_iterator_tag'] => $rotation_index,
            )
        );
    }

}

