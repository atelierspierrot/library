#!/usr/bin/env php
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_STRICT);

if (empty($argv) || count($argv)<2) {
    echo <<<EOT
usage: wrap-text.php <input-file> [output-file = input-file] [wrap-length = 75]

EOT;
    exit(1);
}

$input_file     = $argv[1];
$output_file    = isset($argv[2]) ? $argv[2] : $input_file;
$line_length    = isset($argv[3]) ? $argv[3] : 75;

if (!file_exists($input_file) || !is_readable($input_file)) {
    echo "Input file '$input_file' not found or is not readable!";
    exit(1);
}
if (!touch($output_file)) {
    echo "Output file '$output_file' is not writable!";
    exit(1);
}

require_once __DIR__.'/../src/SplClassLoader.php';
$classLoader = new SplClassLoader('Library', __DIR__.'/../src');
$classLoader->register();

$content = file_get_contents($input_file);
//$wrapped_content = \Library\Converter\Html2Text::convert($content, null, $line_length);
$wrapped_content = \Library\Helper\Text::wrap($content, $line_length);
//echo $wrapped_content;
file_put_contents($output_file, $wrapped_content);
