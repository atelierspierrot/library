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


namespace Library\Converter;

use \Library\Helper\Text as TextHelper;

/**
 * Array to INI format converter
 *
 * @author  piwi <me@e-piwi.fr>
 */
class Array2INI
    extends AbstractConverter
{

    public static function convert($data)
    {
        $data = self::_rearrange((array) $data);
        return self::doConvert($data);
    }

    protected static function _rearrange(array $data)
    {
        $array_items = array();
        foreach ($data as $k=>$v) {
            if (is_array($v)) {
                unset($data[$k]);
                $array_items[$k] = $v;
            }
        }
        return array_merge($data, $array_items);
    }

    public static function doConvert(array $data, array $parent = array())
    {
        $output = '';
        foreach ($data as $k => $v) {
            $index = str_replace(' ', '-', $k);
            if (is_array($v)) {
                $sec = array_merge((array) $parent, (array) $index);
                $output .= PHP_EOL . '[' . join('.', $sec) . ']' . PHP_EOL;
                $output .= self::doConvert($v, $sec);
            } else {
                $output .= "$index=";
                if (is_numeric($v) || is_float($v)) {
                    $output .= "$v";
                } elseif (is_bool($v)) {
                    $output .= ($v===true) ? 1 : 0;
                } elseif (is_string($v)) {
                    $output .= "'".addcslashes($v, "'")."'";
                } else {
                    $output .= "$v";
                }
                $output .= PHP_EOL;
            }
        }
        return $output;
    }

}
/*
require_once __DIR__."/../src/SplClassLoader.php";
$classLoader = new SplClassLoader("Library", __DIR__."/../src");
$classLoader->register();

$data = array(
    'mlkj' => 1,
    'iouy' => 'ou fqsjdkflmLKJMlkj ',
    'huiuh' => '<p>Ulkjfds jk <strong>qsdf</strong> uio !</p>',
    3=>'qsdf',
    'test' => array(
        'one'=>'kjh',
        'two'=>'kjh',
        3=>'kjh',
        'four'=>'kjh',
    ),
    'huiuh2' => "<p>Ulkjfds 'jk' <strong>qsdf</strong> uio !</p>",
    'uuuu'=>true,
    'iiii'=>false,
    'qsd qsdf' => array(
        'one'=>'kjh',
        'two'=>'kjh',
        3=>'kjh',
        'four'=>'kjh',
        'test' => array(
            'one'=>'kjh',
            'two'=>'kjh',
            3=>'kjh',
            'four'=>'kjh',
        ),
    ),
);

echo "<pre>";


var_export($data);

echo "<hr />";

$ini = \Library\Converter\Array2INI::convert($data);
echo $ini;

echo "<hr />";

var_export(parse_ini_string($ini, true));

exit('yo');
*/
// Endfile