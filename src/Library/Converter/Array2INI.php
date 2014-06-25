<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */

namespace Library\Converter;

use \Library\Helper\Text as TextHelper;

/**
 * Array to INI format converter
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