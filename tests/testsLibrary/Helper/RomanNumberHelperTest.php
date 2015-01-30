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

namespace testsLibrary\Helper;

use \testsLibrary\TestCase;

class RomanNumberHelperTest
    extends TestCase
{

    public function testOne()
    {
        // test for 2015 : MMXV
        $int = 2015;
        $str = 'MMXV';
        $this->assertTrue(\Library\Helper\RomanNumber::isRomanNumber($str));
        $this->assertFalse(\Library\Helper\RomanNumber::isRomanNumber($str.'k'));
        $this->assertEquals($int, \Library\Helper\RomanNumber::romanToInt($str));
        $this->assertEquals($str, \Library\Helper\RomanNumber::intToRoman($int));
    }

}
