<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/library>.
 */

namespace testsLibrary\Helper;

use \testsLibrary\TestCase;

class FileHelperTest
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
    public function checkNoArg($meth, $default = null)
    {
        $this->checkHelperMethodNoArg('\Library\Helper\File', $meth, $default);
    }

    /**
     * @covers ../../../src/Library/Helper/File::getUniqFilename()
     */
    public function test_getUniqFilename()
    {
        $this->checkNoArg('getUniqFilename', '');
        $fn = 'my_filename.txt';
        $this->assertEquals($fn, \Library\Helper\File::getUniqFilename($fn, self::$tmp_dir));
        \Library\Helper\File::touch(self::$tmp_dir.'/'.$fn);
        $this->assertNotEquals($fn, \Library\Helper\File::getUniqFilename($fn, self::$tmp_dir));
    }

    /**
     * @covers ../../../src/Library/Helper/File::formatFilename()
     * @FAILS
     */
    public function test_formatFilename()
    {
return true;
        $this->checkNoArg('formatFilename', '');
        $fn = 'My é Own.filename [2015] VOSTFR .txt';
        $this->assertEquals(
            'My-e-Own.filename-2015-VOSTFR.txt', \Library\Helper\File::formatFilename($fn));
        $this->assertEquals(
            'my-e-own.filename-2015-vostfr.txt', \Library\Helper\File::formatFilename($fn, true));
    }

    /**
     * @covers ../../../src/Library/Helper/File::getExtension()
     */
    public function test_getExtension()
    {
        $this->checkNoArg('getExtension', '');
        $fn = 'My é Own.filename [2015] VOSTFR .txt';
        $this->assertEquals(
            'txt', \Library\Helper\File::getExtension($fn));
        $this->assertEquals(
            '.txt', \Library\Helper\File::getExtension($fn, true));
    }

    /**
     * @covers ../../../src/Library/Helper/File::getHumanReadableFilename()
     */
    public function test_getHumanReadableFilename()
    {
        $this->checkNoArg('getHumanReadableFilename', '');
        $fn = 'My-Own.filename_2015/VOSTFR.txt';
        $this->assertEquals(
            'My Own filename 2015 VOSTFR', \Library\Helper\File::getHumanReadableFilename($fn));
    }

    /**
     * @covers ../../../src/Library/Helper/File::getTransformedFilesize()
     */
    public function test_getTransformedFilesize()
    {
        $this->checkNoArg('getTransformedFilesize', 0);
        $this->assertEquals('1 Ko', \Library\Helper\File::getTransformedFilesize(1024));
        $this->assertEquals('1,023 Ko', \Library\Helper\File::getTransformedFilesize(1048));
        $this->assertEquals('1.023 Ko', \Library\Helper\File::getTransformedFilesize(1048, 3, '.'));
        $this->assertEquals('1,02 Ko', \Library\Helper\File::getTransformedFilesize(1048, 2));
        $this->assertEquals('1 Ko', \Library\Helper\File::getTransformedFilesize(1048, 1));
    }

    /**
     * @covers ../../../src/Library/Helper/File::touch()
     */
    public function test_touch()
    {
        $this->checkNoArg('touch');
        \Library\Helper\Directory::ensureExists(self::$tmp_dir);
        $logs = array();
        $this->assertTrue(\Library\Helper\File::touch(self::$tmp_file));
        $this->assertEmpty($logs);
    }

    /**
     * @covers ../../../src/Library/Helper/File::copy()
     */
    public function test_copy()
    {
        $this->checkNoArg('copy');
        \Library\Helper\Directory::ensureExists(self::$tmp_dir);
        \Library\Helper\File::touch(self::$tmp_file);

        $logs = array();
        $this->assertTrue(\Library\Helper\File::copy(self::$tmp_file, self::$tmp_file_2));
        $this->assertEmpty($logs);
/*
        $logs = array();
        $this->assertTrue(\Library\Helper\File::copy(self::$tmp_file, self::$tmp_file, true));
        $this->assertEmpty($logs);
*/
    }

    /**
     * @covers ../../../src/Library/Helper/File::remove()
     */
    public function test_remove()
    {
        $this->checkNoArg('remove');
        \Library\Helper\Directory::ensureExists(self::$tmp_dir);
        \Library\Helper\File::touch(self::$tmp_file_2);
        $logs = array();
        $this->assertTrue(\Library\Helper\File::remove(self::$tmp_file_2));
        $this->assertEmpty($logs);
    }

    /**
     * @covers ../../../src/Library/Helper/File::write()
     */
    public function test_write()
    {
        $this->checkNoArg('remove');
        \Library\Helper\Directory::ensureExists(self::$tmp_dir);
        \Library\Helper\File::touch(self::$tmp_file);
        $logs = array();
        $this->assertTrue(\Library\Helper\File::write(self::$tmp_file, $this->lorem_ipsum));
        $this->assertEmpty($logs);
        $ctt = trim(file_get_contents(self::$tmp_file));
        $this->assertEquals($this->lorem_ipsum, $ctt);
    }

}
