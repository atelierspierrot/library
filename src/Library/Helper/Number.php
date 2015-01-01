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
    public static function isOdd($val)
    {
        return (bool) !($val % 2 == 0);
    }

    /**
     * Test if an integer is an "even number"
     *
     * @param   int     $val
     * @return  bool
     */
    public static function isEven($val)
    {
        return (bool) ($val % 2 == 0);
    }

    /**
     * Test if an integer is a "prime number"
     *
     * @param   int $val
     * @return  bool
     */
    public static function isPrime($val)
    {
        if (
            ($val<=1) ||
            ($val>2 && ($val%2)===0)
        ) return false;
        for ($i=2;$i<$val;$i++) {
            if (($val%$i)===0) return false;
        }
        return true;
    }

    /**
     * Test if an integer is a "primordial number"
     *
     * @param   int $val
     * @return  bool
     */
    public static function isPrimordial($val)
    {
        $r = 0;
        $t = $val;
        while ($t>0) {
            $d = $t%10;
            $t = $t/10;
            $r = ($r*10)+$d;
        }
        return (bool) ($r==$val);
    }

    /**
     * Get the `$val` element of the Fibonacci suite
     *
     * @param   int $val
     * @return  int
     */
    public static function getFibonacciItem($val)
    {
        if ($val==0) return 0;
        elseif ($val==1) return 1;
        elseif ($val>1) return (self::getFibonacciItem($val-1) + self::getFibonacciItem($val-2));
        return null;
    }

    /**
     * Luhn formula
     * 
     * @param   int     $val
     * @return  int
     */
    public static function getLuhnKey($val)
    {
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
        if($luhn_key == 10) {
            $luhn_key = 0;
        }
        return $luhn_key;
    }

    /**
     * Check id last number in a suite is a Luhn key
     *
     * @param   int     $val    The number to check INCLUDING Luhn's key at last
     * @return  bool
     */
    public static function isLuhn($val)
    {
        $_num = substr($val, 0, strlen($val)-1);
        return (intval($val) == intval($_num.self::getLuhnKey($_num)));
    }

    /**
     * Calculate the sum of the digits of a number (its absolute entire value)
     *
     * @param $a
     * @return int
     */
    public static function getSumOfDigits($a)
    {
        $nbs = str_split(pow(abs($a), 0));
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
     * @param $a
     * @return bool
     */
    public static function isSelfDescribing($a)
    {
        for ($i=0; $i<strlen($a); $i++) {
            $val = $a{$i};
            $occ = substr_count($a, $i);
            if ($val != $occ) {
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
     * @param array $a
     * @return bool
     */
    public static function isJollyJumperSeries(array $a)
    {
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

    public static $romans_numbers = array(
        1,5,10,50,100,500,1000
    );

    public static $romans_letters = array(
        'I','V','X','L','C','D','M'
    );

    /**
     * Get the roman notation of a number inferior to 5000
     *
     * @param $a
     * @return string
     */
    public static function getRomanNumeralsNotation($a)
    {
        if ($a>4999) return null;
        $ctt = '';
        $counter = 1;
        for ($i=(strlen($a)-1); $i>=0; $i--) {
            $tmp_ctt = '';
            $char = $a{$i};
            if ($char>0 && $char<4) {
                $index = array_search($counter, self::$romans_numbers);
                $tmp_ctt .= str_pad(self::$romans_letters[$index], $char, self::$romans_letters[$index]);
            } elseif (3<$char && $char<9) {
                $index = array_search($counter, self::$romans_numbers);
                if ($char==4) {
                    $tmp_ctt .= self::$romans_letters[$index];
                }
                $tmp_ctt .= self::$romans_letters[$index+1];
                if ($char>5) {
                    $tmp_ctt .= str_pad(self::$romans_letters[$index], ($char-5), self::$romans_letters[$index]);
                }
            } elseif ($char==9) {
                $index = array_search($counter, self::$romans_numbers);
                $tmp_ctt .= self::$romans_letters[$index].self::$romans_letters[$index+2];
            }
            $counter = $counter*10;
            $ctt = $tmp_ctt.$ctt;
        }
        return $ctt;
    }

}

// Endfile
