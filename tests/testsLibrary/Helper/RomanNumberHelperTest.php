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

class RomanNumberHelperTest
    extends TestCase
{

    // test for 2015 : MMXV
    public $int = 2015;
    public $str = 'MMXV';

    /**
     * Test if method `\Library\Helper\RomanNumber::$meth()` with no argument returns `null` with no error
     *
     * @param $meth
     * @param $default
     */
    public function checkNoArg($meth, $default = null)
    {
        $this->checkHelperMethodNoArg('\Library\Helper\RomanNumber', $meth, $default);
    }

    /**
     * @covers ../../../src/Library/Helper/RomanNumber::isRomanNumber()
     */
    public function testOne()
    {
        $this->checkNoArg('isRomanNumber');
        $this->assertTrue(\Library\Helper\RomanNumber::isRomanNumber($this->str));
        $this->assertFalse(\Library\Helper\RomanNumber::isRomanNumber($this->str.'k'));
    }

    /**
     * @covers ../../../src/Library/Helper/RomanNumber::romanToInt()
     */
    public function testTwo()
    {
        $this->checkNoArg('romanToInt');
        $this->assertEquals($this->int, \Library\Helper\RomanNumber::romanToInt($this->str));
        $this->assertNotEquals($this->int, \Library\Helper\RomanNumber::romanToInt($this->str.'L'));
        $this->assertNotEquals($this->int+1, \Library\Helper\RomanNumber::romanToInt($this->str));
    }

    /**
     * @covers ../../../src/Library/Helper/RomanNumber::intToRoman()
     */
    public function testThree()
    {
        $this->checkNoArg('intToRoman');
        $this->assertEquals($this->str, \Library\Helper\RomanNumber::intToRoman($this->int));
        $this->assertNotEquals($this->str, \Library\Helper\RomanNumber::intToRoman($this->int+1));
        $this->assertNotEquals($this->str.'K', \Library\Helper\RomanNumber::intToRoman($this->int));
    }

}
