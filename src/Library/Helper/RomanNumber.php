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
 * RomanNumber helper
 *
 * As for all helpers, all methods are statics.
 *
 * For convenience, the best practice is to use:
 *
 *     use Library\Helper\RomanNumber as RomanNumberHelper;
 *
 * @link    <http://en.wikipedia.org/wiki/Roman_numerals>
 * @author  piwi <me@e-piwi.fr>
 */
class RomanNumber
{

    /**
     * @var array
     */
    public static $romans_numbers = array(0,1,5,10,50,100,500,1000);

    /**
     * @var array
     */
    public static $romans_letters = array('N','I','V','X','L','C','D','M');

    /**
     * @var string
     * @see http://stackoverflow.com/questions/6265596/how-to-convert-a-roman-numeral-to-integer-in-php/6291089#6291089
     */
    public static $roman_regex = '/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';

    /**
     * @param $roman
     * @return bool
     */
    public static function isRomanNumber($roman = null)
    {
        if (is_null($roman)) {
            return false;
        }
        return (bool) (preg_match(self::$roman_regex, $roman) > 0);
    }

    /**
     * Get the integer equivalent from roman notation
     *
     * @param $str
     * @return bool|int
     */
    public static function romanToInt($str = null)
    {
        if (is_null($str)) {
            return null;
        }
        if ($str==self::$romans_letters[0]) {
            return 0;
        }
        if (!self::isRomanNumber($str)) {
            return false;
        }
        $result = 0;
        $length = strlen($str);
        for ($i=0; $i < $length; $i++) {
            $index      = array_search($str{$i}, self::$romans_letters);
            $value      = self::$romans_numbers[$index];
            $nextval    = null;
            if ($i < ($length - 1)) {
                $nextind    = array_search($str{$i + 1}, self::$romans_letters);
                $nextval    = self::$romans_numbers[$nextind];
            }
            $result     += (!is_null($nextval) && $nextval > $value) ? -$value : $value;
        }
        return $result;
    }

    /**
     * Get the roman notation of a number inferior to 5000
     *
     * @param $a
     * @return string
     */
    public static function intToRoman($a = null)
    {
        if (is_null($a)) {
            return null;
        }
        if ($a>4999) {
            return null;
        }
        if ($a==0) {
            return self::$romans_letters[0];
        }
        $a = (string) $a;
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
