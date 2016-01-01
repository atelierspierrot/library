#!/usr/bin/env php
<?php

#############
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_STRICT);
require_once __DIR__.'/../src/SplClassLoader.php';
$classLoader = new SplClassLoader('Library', __DIR__.'/../src');
$classLoader->register();
#############

$n = isset($argv[1]) ? $argv[1] : '0';

$roman = \Library\Helper\RomanNumber::intToRoman($n);
echo $roman.PHP_EOL;

$isit = \Library\Helper\RomanNumber::isRomanNumber($roman);
echo var_export($isit, 1).PHP_EOL;

$isit = \Library\Helper\RomanNumber::isRomanNumber($n);
echo var_export($isit, 1).PHP_EOL;

$int = \Library\Helper\RomanNumber::romanToInt($roman);
echo $int.PHP_EOL;

#############
exit(PHP_EOL.'-- endrun --'.PHP_EOL);
