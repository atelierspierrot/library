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
            if (!is_null($default)) {
                $this->assertEquals(
                    $default, call_user_func(array($classname, $meth))
                );
            } else {
                $this->assertEmpty(
                    call_user_func(array($classname, $meth))
                );
            }
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
