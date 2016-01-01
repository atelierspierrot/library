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

namespace Library\Tool;

/**
 * @author  piwi <me@e-piwi.fr>
 */
class MorseCode
{

    public static $CHARACTERS = array(
        'a' => array('dot','dash'),
        'b' => array('dash','dot','dot','dot'),
        'c' => array('dash','dot','dash','dot'),
        'd' => array('dash','dot','dot'),
        'e' => array('dot'),
        'f' => array('dot','dot','dash','dot'),
        'g' => array('dash','dash','dot'),
        'h' => array('dot','dot','dot','dot'),
        'i' => array('dot','dot'),
        'j' => array('dot','dash','dash','dash'),
        'k' => array('dash','dot','dash'),
        'l' => array('dot','dash','dot','dot'),
        'm' => array('dash','dash'),
        'n' => array('dash','dot'),
        'o' => array('dash','dash','dash'),
        'p' => array('dot','dash','dash','dot'),
        'q' => array('dash','dash','dot','dash'),
        'r' => array('dot','dash','dot'),
        's' => array('dot','dot','dot'),
        't' => array('dash'),
        'u' => array('dot','dot','dash'),
        'v' => array('dot','dot','dot','dash'),
        'w' => array('dot','dash','dash'),
        'x' => array('dash','dot','dot','dash'),
        'y' => array('dash','dot','dash','dash'),
        'z' => array('dash','dash','dot','dot'),
        '1' => array('dot','dash','dash','dash','dash'),
        '2' => array('dot','dot','dash','dash','dash'),
        '3' => array('dot','dot','dot','dash','dash'),
        '4' => array('dot','dot','dot','dot','dash'),
        '5' => array('dot','dot','dot','dot','dot'),
        '6' => array('dash','dot','dot','dot','dot'),
        '7' => array('dash','dash','dot','dot','dot'),
        '8' => array('dash','dash','dash','dot','dot'),
        '9' => array('dash','dash','dash','dash','dot'),
        '0' => array('dash','dash','dash','dash','dash'),
        '.' => array('dot','dash','dot','dash','dot','dash'),
        ',' => array('dash','dash','dot','dot','dash','dash'),
        '?' => array('dot','dot','dash','dash','dot','dot'),
        "'" => array('dot','dash','dash','dash','dash','dot'),
        '!' => array('dash','dot','dash','dot','dash','dash'),
        '/' => array('dash','dot','dot','dash','dot'),
        '(' => array('dash','dot','dash','dash','dot'),
        ')' => array('dash','dot','dash','dash','dot','dash'),
        '&' => array('dot','dash','dot','dot','dot'),
        ':' => array('dash','dash','dash','dot','dot','dot'),
        ';' => array('dash','dot','dash','dot','dash','dot'),
        '=' => array('dash','dot','dot','dot','dash'),
        '+' => array('dot','dash','dot','dash','dot'),
        '-' => array('dash','dot','dot','dot','dot','dash'),
        '_' => array('dot','dot','dash','dash','dot','dash'),
        '"' => array('dot','dash','dot','dot','dash','dot'),
        '$' => array('dot','dot','dot','dash','dot','dot','dash'),
        '@' => array('dot','dash','dash','dot','dash','dot'),
    );

    public static $DOT_CHARACTER                 = '.';

    public static $DASH_CHARACTER                = '-';

    public static $SPACE_CHARACTER               = ' ';

    public static $DOT_LENGTH                    = 1;

    public static $DASH_LENGTH                   = 3;

    public static $IN_LETTER_SPACE_LENGTH        = 1;

    public static $LETTERS_SPACING_LENGTH        = 3;

    public static $WORDS_SPACING_LENGTH          = 7;

    public static function getLetter($char)
    {
        $ctt = '';
        if (array_key_exists($char, self::$CHARACTERS)) {
            foreach (self::$CHARACTERS[$char] as $part) {
                $ctt .= ($part=='dot' ? self::$DOT_CHARACTER : self::$DASH_CHARACTER)
                    .str_pad(self::$SPACE_CHARACTER, self::$IN_LETTER_SPACE_LENGTH, self::$SPACE_CHARACTER);
            }
        }
        return $ctt;
    }

    public static function getWord($word)
    {
        $ctt = '';
        for ($i=0; $i<strlen($word); $i++) {
            $ctt .= self::getLetter($word{$i})
                .str_pad(self::$SPACE_CHARACTER, self::$LETTERS_SPACING_LENGTH, self::$SPACE_CHARACTER);
        }
        return $ctt;
    }

    public static function getString($str)
    {
        $ctt = '';
        $words = explode(' ', $str);
        foreach ($words as $word) {
            $ctt .= self::getWord($word)
                .str_pad(self::$SPACE_CHARACTER, self::$WORDS_SPACING_LENGTH, self::$SPACE_CHARACTER);
        }
        return $ctt;
    }

    public static function encode($str)
    {
        return self::getString($str);
    }

}

