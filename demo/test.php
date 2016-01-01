<?php

/**
 * Show errors at least initially
 *
 * `E_ALL` => for hard dev
 * `E_ALL & ~E_STRICT` => for hard dev in PHP5.4 avoiding strict warnings
 * `E_ALL & ~E_NOTICE & ~E_STRICT` => classic setting
 */
@ini_set('display_errors', '1'); @error_reporting(E_ALL);
//@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_STRICT);
//@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

/**
 * Set a default timezone to avoid PHP5 warnings
 */
$dtmz = @date_default_timezone_get();
date_default_timezone_set($dtmz?:'Europe/Paris');

/**
 * For security, transform a realpath as '/[***]/package_root/...'
 *
 * @param string $path
 * @param int $depth_from_root
 *
 * @return string
 */
function _getSecuredRealPath($path, $depth_from_root = 1)
{
    $ds = DIRECTORY_SEPARATOR;
    $parts = explode($ds, realpath('.'));
    for ($i=0; $i<=$depth_from_root; $i++) {
        array_pop($parts);
    }
    return str_replace(join($ds, $parts), $ds.'[***]', $path);
}

// arguments settings
$arg_ln = isset($_GET['ln']) ? $_GET['ln'] : 'en';

function getPhpClassManualLink($class_name, $ln='en')
{
    return sprintf('http://php.net/manual/%s/class.%s.php', $ln, strtolower($class_name));
}

if (file_exists($_f = __DIR__."/../vendor/autoload.php")) {
    require_once $_f;
} else {
    trigger_error('You need to run Composer on your package to install dependencies!', E_USER_ERROR);
}

$logs = array();
$dir = __DIR__.'/test_tmp';

$act = 'r';

switch ($act) {
    case 'c': default:
        \Library\Helper\Directory::ensureExists($dir);
        \Library\Helper\File::touch($dir.'/test1');
        \Library\Helper\File::touch($dir.'/test2');
        \Library\Helper\File::touch($dir.'/test/test1');
        \Library\Helper\File::touch($dir.'/test/test2');
        \Library\Helper\Directory::chmod($dir, 777, true, 766, $logs);
        break;
    case 'p':
        \Library\Helper\Directory::purge($dir, $logs);
        break;
    case 'r':
        \Library\Helper\Directory::remove($dir, $logs);
        break;
}
var_export($logs);

exit('end');
