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

class NumberHelperTest
    extends TestCase
{

    public function testOne()
    {
        // is even
        $this->assertTrue(\Library\Helper\Number::isEven(2));
        $this->assertTrue(\Library\Helper\Number::isEven(12));
        $this->assertFalse(\Library\Helper\Number::isEven(1));
        $this->assertFalse(\Library\Helper\Number::isEven(15));
        // is add
        $this->assertTrue(\Library\Helper\Number::isOdd(1));
        $this->assertTrue(\Library\Helper\Number::isOdd(17));
        $this->assertFalse(\Library\Helper\Number::isOdd(2));
        $this->assertFalse(\Library\Helper\Number::isOdd(22));
        // is prime
        $this->assertTrue(\Library\Helper\Number::isPrime(2));
        $this->assertTrue(\Library\Helper\Number::isPrime(7));
        $this->assertFalse(\Library\Helper\Number::isPrime(4));
        $this->assertFalse(\Library\Helper\Number::isPrime(16));
        // is jolly jumper
        $this->assertTrue(\Library\Helper\Number::isJollyJumperSeries(array(4,1,4,2,3)));
        $this->assertFalse(\Library\Helper\Number::isJollyJumperSeries(array(19,22,24,21)));
    }

}
