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
