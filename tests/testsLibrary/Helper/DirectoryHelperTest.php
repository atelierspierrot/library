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

namespace testsLibrary\Helper;

use \testsLibrary\TestCase;

class DirectoryHelperTest
    extends TestCase
{

    /**
     * Prepare file names
     */
    public static function setUpBeforeClass()
    {
        parent::prepareTempDir();
    }

    /**
     * Cleanup test files
     */
    public static function tearDownAfterClass()
    {
        parent::cleanTempDir();
    }

    /**
     * Test if method `\Library\Helper\Directory::$meth()` with no argument returns `null` with no error
     *
     * @param $meth
     */
    public function checkNoArg($meth)
    {
        $this->checkHelperMethodNoArg('\Library\Helper\Directory', $meth);
    }

    /**
     * @covers ../../../src/Library/Helper/Directory::slashDirname()
     */
    public function test_slashDirname()
    {
        $this->checkNoArg('slashDirname');
        $this->assertEquals('my/path/', \Library\Helper\Directory::slashDirname('my/path'));
        $this->assertEquals('my/path/', \Library\Helper\Directory::slashDirname('my/path/'));
        $this->assertEquals('my/path/', \Library\Helper\Directory::slashDirname('my/path//'));
        $this->assertNotEquals('my/path', \Library\Helper\Directory::slashDirname('my/path'));
    }

    /**
     * @covers ../../../src/Library/Helper/Directory::ensureExists()
     */
    public function test_ensureExists()
    {
        $this->checkNoArg('ensureExists');
        $this->assertTrue(\Library\Helper\Directory::ensureExists(self::$tmp_dir));
        $this->assertTrue(file_exists(self::$tmp_dir));
    }

    /**
     * @covers ../../../src/Library/Helper/Directory::chmod()
     * @FAILS
     */
    public function test_chmod()
    {
return true;
        $this->checkNoArg('chmod');
        \Library\Helper\File::touch(self::$tmp_file);
        $logs = array();
        $this->assertTrue(\Library\Helper\Directory::chmod(self::$tmp_file, 777, false, 766, $logs));
        $this->assertEmpty($logs);
    }

    /**
     * @covers ../../../src/Library/Helper/Directory::purge()
     */
    public function test_purge()
    {
        $this->checkNoArg('purge');
        \Library\Helper\File::touch(self::$tmp_file);
        $logs = array();
        $this->assertTrue(\Library\Helper\Directory::purge(self::$tmp_dir, $logs));
        $this->assertEmpty($logs);
    }

    /**
     * @covers ../../../src/Library/Helper/Directory::remove()
     */
    public function test_remove()
    {
        $this->checkNoArg('remove');
        \Library\Helper\File::touch(self::$tmp_file);
        $logs = array();
        $this->assertTrue(\Library\Helper\Directory::remove(self::$tmp_dir, $logs));
        $this->assertEmpty($logs);
    }

}
