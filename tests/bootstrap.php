<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/library>.
 */

require_once __DIR__.'/../src/SplClassLoader.php';
$classLoader = new SplClassLoader('Library', __DIR__.'/../src');
$classLoader->register();
$classLoader_tests = new SplClassLoader('testsLibrary', __DIR__.'/../tests');
$classLoader_tests->register();
