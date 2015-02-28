#!/usr/bin/env php
<?php
/**
 * Show errors at least initially
 *
 * `E_ALL` => for hard dev
 * `E_ALL & ~E_STRICT` => for hard dev in PHP5.4 avoiding strict warnings
 * `E_ALL & ~E_NOTICE & ~E_STRICT` => classic setting
 */
//@ini_set('display_errors','1'); @error_reporting(E_ALL);
//@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_STRICT);
@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

/**
 * Set a default timezone to avoid PHP5 warnings
 */
$dtmz = @date_default_timezone_get();
date_default_timezone_set($dtmz?:'Europe/Paris');

require_once __DIR__."/../src/SplClassLoader.php";
$classLoader = new SplClassLoader("Library", __DIR__."/../src");
$classLoader->register();


// MorseCode test
$string = 'Lorem ipsum dolor site amet';


echo \Library\Tool\MorseCode::$DOT_CHARACTER.PHP_EOL;
echo \Library\Tool\MorseCode::$DASH_CHARACTER.PHP_EOL;
echo \Library\Tool\MorseCode::getLetter('a').PHP_EOL;
echo \Library\Tool\MorseCode::encode($string).PHP_EOL;


exit(PHP_EOL);
// Endfile