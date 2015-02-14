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

namespace testsLibrary;

abstract class TestCase
    extends \PHPUnit_Framework_TestCase
{

    public $lorem_ipsum =
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    public $short_lorem_ipsum =
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
    public $long_lorem_ipsum =
        "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur.";

    /**
     * @param $classname
     * @param $meth
     * @param null $default
     */
    public function checkHelperMethodNoArg($classname, $meth, $default = null)
    {
        try {
            $this->assertEquals(
                $default, call_user_func(array($classname, $meth))
            );
        } catch (\Exception $e) {
            $this->fail("Failure of '$classname::$meth()' with no arg!");
        }
    }

    public static $base_tmp_dir;
    public static $tmp_dir;
    public static $tmp_file;
    public static $tmp_file_2;

    /**
     * Prepare file paths
     */
    public static function prepareTempDir()
    {
//        self::$base_tmp_dir = sys_get_temp_dir();
        self::$base_tmp_dir = realpath(__DIR__.'/../temp');
        self::$tmp_dir = self::$base_tmp_dir.'/'.uniqid();
        self::$tmp_file = self::$tmp_dir.'/'.uniqid().'.txt';
        self::$tmp_file_2 = self::$tmp_dir.'/'.uniqid().'-2.txt';
    }

    /**
     * Cleanup test files
     */
    public static function cleanTempDir()
    {
        @exec('rm -rf '.self::$tmp_dir);
    }

}
