<?php

define('DEMO_BASEDIR', __DIR__);

if (file_exists($demobuilder = __DIR__.'/vendor/atelierspierrot/demo-builder/demo-builder.php')) {
    require_once $demobuilder;
} else {
    die(<<<TEXT
You need to install the demo dependencies with <a href="http://getcomposer.org/">Composer</a> running:
<pre>
cd demo
composer install
</pre>
TEXT
    );
}
