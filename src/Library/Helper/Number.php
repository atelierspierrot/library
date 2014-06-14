<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
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
     * @param   numeric     $val
     * @return  numeric
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
     * @param   numeric     $val    The number to check INCLUDING Luhn's key at last
     * @return  bool
     */
    public static function function isLuhn($val)
    {
        $_num = substr($val, 0, strlen($val)-1);
        return (intval($val) == intval($_num.self::getLuhnKey($_num)));
    }

}

// Endfile
