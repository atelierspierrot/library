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

class NumberHelperTest
    extends TestCase
{

    /**
     * Test if method `\Library\Helper\Number::$meth()` with no argument returns `null` with no error
     *
     * @param $meth
     */
    public function checkNoArg($meth)
    {
        $this->checkHelperMethodNoArg('\Library\Helper\Number', $meth);
    }

    /**
     * @covers ../../../src/Library/Helper/Number::isEven()
     */
    public function testIsEven()
    {
        $this->checkNoArg('isEven');
        $this->assertTrue(\Library\Helper\Number::isEven(2));
        $this->assertTrue(\Library\Helper\Number::isEven(12));
        $this->assertFalse(\Library\Helper\Number::isEven(1));
        $this->assertFalse(\Library\Helper\Number::isEven(15));
    }

    /**
     * @covers ../../../src/Library/Helper/Number::isOdd()
     */
    public function testIsOdd()
    {
        $this->checkNoArg('isOdd');
        $this->assertTrue(\Library\Helper\Number::isOdd(1));
        $this->assertTrue(\Library\Helper\Number::isOdd(17));
        $this->assertFalse(\Library\Helper\Number::isOdd(2));
        $this->assertFalse(\Library\Helper\Number::isOdd(22));
    }

    /**
     * @covers ../../../src/Library/Helper/Number::isPrime()
     */
    public function testIsPrime()
    {
        $this->checkNoArg('isPrime');
        $this->assertTrue(\Library\Helper\Number::isPrime(2));
        $this->assertTrue(\Library\Helper\Number::isPrime(7));
        $this->assertFalse(\Library\Helper\Number::isPrime(4));
        $this->assertFalse(\Library\Helper\Number::isPrime(16));
    }

    /**
     * @covers ../../../src/Library/Helper/Number::isJollyJumperSeries()
     */
    public function testIsJollyJumper()
    {
        $this->checkNoArg('isJollyJumperSeries');
        $this->assertTrue(\Library\Helper\Number::isJollyJumperSeries(array(4, 1, 4, 2, 3)));
        $this->assertFalse(\Library\Helper\Number::isJollyJumperSeries(array(19, 22, 24, 21)));
    }

    /**
     * @covers ../../../src/Library/Helper/Number::getFibonacciItem()
     */
    public function testFibonacciSuite()
    {
        $this->checkNoArg('getFibonacciItem');
        $this->assertEquals(144, \Library\Helper\Number::getFibonacciItem(12));
        $this->assertEquals(75025, \Library\Helper\Number::getFibonacciItem(25));
        $this->assertNotEquals(6764, \Library\Helper\Number::getFibonacciItem(20));
    }

    /**
     * @covers ../../../src/Library/Helper/Number::getSumOfDigits()
     */
    public function testSumOfDigits()
    {
        $this->checkNoArg('getSumOfDigits');
        $this->assertEquals(5, \Library\Helper\Number::getSumOfDigits(23));
        $this->assertEquals(19, \Library\Helper\Number::getSumOfDigits(496));
        $this->assertNotEquals(4, \Library\Helper\Number::getSumOfDigits(23));
        $this->assertNotEquals(20, \Library\Helper\Number::getSumOfDigits(496));
    }

    /**
     * @covers ../../../src/Library/Helper/Number::getLuhnKey()
     * @covers ../../../src/Library/Helper/Number::isLuhn()
     */
    public function testLuhnKey()
    {
        $this->checkNoArg('getLuhnKey');
        $this->assertEquals(3, \Library\Helper\Number::getLuhnKey(7992739871));
        $this->assertNotEquals(3, \Library\Helper\Number::getLuhnKey(799273981));

        $this->checkNoArg('isLuhn');
        $this->assertTrue(\Library\Helper\Number::isLuhn(79927398713));
        $this->assertFalse(\Library\Helper\Number::isLuhn(79927398711));
    }

    /**
     * @covers ../../../src/Library/Helper/Number::isSelfDescribing()
     */
    public function testSelfDescribe()
    {
        $this->checkNoArg('isSelfDescribing');
        $this->assertTrue(\Library\Helper\Number::isSelfDescribing(2020));
        $this->assertFalse(\Library\Helper\Number::isSelfDescribing(22));
        $this->assertTrue(\Library\Helper\Number::isSelfDescribing(1210));
    }

    /**
     * @covers ../../../src/Library/Helper/Number::isPalindromic()
    public function testIsPalindrome()
    {
    $this->checkNoArg('isPalindromic');
    $this->assertTrue(\Library\Helper\Number::isPalindromic(151));
    $this->assertFalse(\Library\Helper\Number::isPalindromic(200));
    }
     */
}
