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


namespace Library\Helper;

/**
 * Number helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\Number as NumberHelper;
 *
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class Number
{

    /**
     * Test if an integer is an "odd number"
     *
     * @param   int     $val
     * @return  bool
     */
    public static function isOdd($val = null)
    {
        if (is_null($val)) {
            return null;
        }
        return (bool) !($val % 2 == 0);
    }

    /**
     * Test if an integer is an "even number"
     *
     * @param   int     $val
     * @return  bool
     */
    public static function isEven($val = null)
    {
        if (is_null($val)) {
            return null;
        }
        return (bool) ($val % 2 == 0);
    }

    /**
     * Test if an integer is a "prime number"
     *
     * @param   int $val
     * @return  bool
     */
    public static function isPrime($val = null)
    {
        if (is_null($val)) {
            return null;
        }
        if (
            ($val<=1) ||
            ($val>2 && ($val%2)===0)
        ) {
            return false;
        }
        for ($i=2;$i<$val;$i++) {
            if (($val%$i)===0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the `$val` element of the Fibonacci suite
     *
     * @param   int $val
     * @return  int
     */
    public static function getFibonacciItem($val = null)
    {
        if (is_null($val)) {
            return null;
        }
        if ($val==0) {
            return 0;
        } elseif ($val==1) {
            return 1;
        } elseif ($val>1) {
            return (self::getFibonacciItem($val-1) + self::getFibonacciItem($val-2));
        }
        return null;
    }

    /**
     * Luhn formula: get the Luhn digit of an integer
     *
     * 7992739871 => 3
     *
     * @see <http://en.wikipedia.org/wiki/Luhn_algorithm>
     * @param   int     $val
     * @return  int
     */
    public static function getLuhnKey($val = null)
    {
        if (is_null($val)) {
            return null;
        }
        $val = $val.'0';
        $length = strlen($val);
        $checksum = 0;
        for ($i=($length - 1); $i>=0; $i--) { 
            $digit = $val[$i];
            if ((($length - $i) % 2) == 0) {
                $digit = $digit * 2;
                if ($digit > 9) {
                    $digit = $digit - 9;
                }
            }
            $checksum += $digit;
        }
        $luhn_key = 10 - ( $checksum % 10 );
        if ($luhn_key == 10) {
            $luhn_key = 0;
        }
        return $luhn_key;
    }

    /**
     * Check that the last number in a suite is its Luhn key
     *
     * @param   int     $val    The number to check INCLUDING Luhn's key at last
     * @return  bool
     */
    public static function isLuhn($val = null)
    {
        if (is_null($val)) {
            return null;
        }
        $_num = substr($val, 0, strlen($val)-1);
        return (bool) (intval($val) == intval($_num.self::getLuhnKey($_num)));
    }

    /**
     * Calculate the sum of the digits of a number (its absolute entire value)
     *
     * @param $val
     * @return int
     */
    public static function getSumOfDigits($val = null)
    {
        if (is_null($val)) {
            return null;
        }
        $nbs = str_split(pow(abs($val), 1));
        $result = 0;
        foreach ($nbs as $nb) {
            $result += (int) $nb;
        }
        return $result;
    }

    /**
     * Test if a number is "self-describing":
     *
     * assuming digit positions are labeled 0 to N-1,
     * the digit in each position is equal to the number of times that digit appears in the number
     *
     * 2020 => true
     * 22 => false
     * 1210 => true
     *
     * @param $val
     * @return bool
     */
    public static function isSelfDescribing($val = null)
    {
        if (is_null($val)) {
            return null;
        }
        $val = (string) $val;
        for ($i=0; $i<strlen($val); $i++) {
            $_val   = $val{$i};
            $occ    = substr_count($val, $i);
            if ($_val != $occ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Test if a series of numbers is a "Jolly Jumper":
     *
     * A sequence of n > 0 integers where the absolute values of the
     * differences between successive elements take on all possible values 1 through n - 1.
     *
     * @param array $items
     * @return bool
     */
    public static function isJollyJumperSeries(array $items = array())
    {
        if (empty($items)) {
            return null;
        }
        if (count($items)==1) {
            return true;
        }
        $diffs = array();
        for ($i=0; $i<count($items)-1; $i++) {
            $diffs[] = abs($items[$i] - $items[$i+1]);
        }
        $diffs = array_filter($diffs);
        $limit = min(max($diffs)+1, count($items));
        $isjj = true;
        for ($i=1; $i<$limit; $i++) {
            if (!in_array($i,$diffs)) {
                $isjj = false;
            }
        }
        return $isjj;
    }

    /**
     * Test if an integer is a "palindromic number"
     *
     * @param   int $val
     * @return  bool
    public static function isPalindromic($val = null)
    {
    if (is_null($val)) {
    return null;
    }
    $r = 0;
    $t = $val;
    while ($t>0) {
    $d = $t%10;
    $t = $t/10;
    $r = ($r*10) + $d;
    }
    return (bool) ($r==$val);
    }
     */

}

// Endfile
