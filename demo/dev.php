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
    for ($i=0; $i<=$depth_from_root; $i++) array_pop($parts);
    return str_replace(join($ds, $parts), $ds.'[***]', $path);
}

function getPhpClassManualLink( $class_name, $ln='en' )
{
    return sprintf('http://php.net/manual/%s/class.%s.php', $ln, strtolower($class_name));
}

?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Test & documentation of PHP "Library" package</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="assets/html5boilerplate/css/normalize.css" />
    <link rel="stylesheet" href="assets/html5boilerplate/css/main.css" />
    <script src="assets/html5boilerplate/js/vendor/modernizr-2.6.2.min.js"></script>
	<link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

    <header id="top" role="banner">
        <hgroup>
            <h1>Tests of PHP <em>Library</em> package</h1>
            <h2 class="slogan">The PHP library package of Les Ateliers Pierrot.</h2>
        </hgroup>
        <div class="hat">
            <p>These pages show and demonstrate the use and functionality of the <a href="https://github.com/atelierspierrot/library">atelierspierrot/library</a> PHP package you just downloaded.</p>
        </div>
    </header>

	<nav>
		<h2>Map of the package</h2>
        <ul id="navigation_menu" class="menu" role="navigation">
            <li><a href="index.php">Homepage</a></li>
            <li><a href="http_fundamental.php">HTTP Fundamental</a></li>
            <li><a href="dev.php">Dev branch</a><ul>
                <li><a href="dev.php#log">Logger</a></li>
                <li><a href="dev.php#rotator">FileRotator</a></li>
            </ul></li>
        </ul>

        <div class="info">
            <p><a href="https://github.com/atelierspierrot/library">See online on GitHub</a></p>
            <p class="comment">The sources of this plugin are hosted on <a href="http://github.com">GitHub</a>. To follow sources updates, report a bug or read opened bug tickets and any other information, please see the GitHub website above.</p>
        </div>

    	<p class="credits" id="user_agent"></p>
	</nav>

    <div id="content" role="main">

        <article>

    <h2 id="notes">First notes</h2>
    <p>This page requires you install the Composer dependencies in <var>dev</var> mode.</p>

	<h2 id="tests">Tests & documentation</h2>

<?php
require_once __DIR__."/../src/SplClassLoader.php";
$classLoader = new SplClassLoader("Library", __DIR__."/../src");
$classLoader->register();
?>
    
<h3 id="log">Library\Logger</h3>

<p>For this demo, log files will be created in "demo/tmp/" directory ; if it does not exist, please create it with a chmod 
of at least 755.</p>

    <pre class="code" data-language="php">
<?php
$classLoader = new SplClassLoader("Psr\Log", __DIR__."/../vendor/psr/log");
$classLoader->register();
echo '$classLoader = new SplClassLoader("Psr\Log", __DIR__."/../vendor/psr/log");'."\n";
echo '$classLoader->register();'."\n";

$log_options = array(
    'directory' => __DIR__.'/tmp'
);
$logger = new Library\Logger($log_options);
echo "\n";
echo '$log_options = array('."\n"
    ."\t".'"directory" => __DIR__."/tmp",'."\n"
    .');'."\n";
echo '$logger = new Library\Logger($log_options);'."\n";

// write a simple log
$ok = $logger->log(Library\Logger::DEBUG, 'my message');
echo "\n";
echo '$ok = $logger->log(Library\Logger::DEBUG, "my message")'."\n";
echo '// => '.var_export($ok,1)."\n";

// write a log message with placeholders
class TestClass
{
    var $msg;
    function __construct( $str )
    {
        $this->msg = $str;
    }
    function __toString()
    {
        return $this->msg;
    }
}
$ok = $logger->log(Library\Logger::DEBUG, "my message with placeholders : {one} and {two}", array(
    'one' => 'my value for first placeholder',
    'two' => new TestClass( 'my test class with a toString method' )
));
echo "\n";
echo '$ok = $logger->log(Library\Logger::DEBUG, "my message with placeholders : {one} and {two}", array('."\n"
    ."\t".'"one" => "my value for first placeholder",'."\n"
    ."\t".'"two" => new TestClass( "my test class with a toString method" )'."\n"
    .'));'."\n";
echo '// => '.var_export($ok,1)."\n";

// write logs in a specific "test" file
$ok = $logger->log(Library\Logger::DEBUG, 'my message', array(), 'test');
echo "\n";
echo '$ok = $logger->log(Library\Logger::DEBUG, "my message", array(), "test");'."\n";
echo '// => '.var_export($ok,1)."\n";

$ok = $logger->log( Library\Logger::DEBUG, '[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
$ok = $logger->log( Library\Logger::ERROR, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
$ok = $logger->log( Library\Logger::INFO, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ', $_GET, 'test' );
echo "\n";
echo '$ok = $logger->log( Library\Logger::DEBUG, "[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf " );'."\n";
echo '$ok = $logger->log( Library\Logger::ERROR, "a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf " );'."\n";
echo '$ok = $logger->log( Library\Logger::INFO, "a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ", $_GET, "test" );'."\n";
echo '// => '.var_export($ok,1)."\n";

// write many logs to test rotation
for ($i=0; $i<1000; $i++)
{
    $ok = $logger->log( Library\Logger::DEBUG, '[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
    $ok = $logger->log( Library\Logger::ERROR, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
    $ok = $logger->log( Library\Logger::INFO, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ', $_GET, 'test' );
}
?>
    </pre>

<h3 id="rotator">Library\FileRotator</h3>

<p>For this demo, files will be created in "demo/tmp/" directory ; if it does not exist, please create it with a chmod 
of at least 755.</p>

    <pre class="code" data-language="php">
<?php
$filename = 'tmp/mytestfile.txt';
$rotator = new Library\FileRotator(
    $filename, Library\FileRotator::ROTATE_PERIODIC, array(
		'period_duration' => 60, // in seconds (here 1 day)
		'filename_mask' => '%s.@date@', // mask used for filenames
		                                // @date@ will be replaced by current date formated with 'date_format'
		                                // @i@ will be replaced by rotation iterator
		'date_format' => 'ymdHi',
		'backup_time' => 10, // number of backuped files
	)
);


// write many logs to test rotation
for ($i=0; $i<1000; $i++)
{
    $ok = $rotator->write('[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
}

    $ok = $rotator->write('[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
sleep(70);
    $ok = $rotator->write('[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
?>
    </pre>

        </article>
    </div>

    <footer id="footer">
		<div class="credits float-left">
		    This page is <a href="" title="Check now online" id="html_validation">HTML5</a> & <a href="" title="Check now online" id="css_validation">CSS3</a> valid.
		</div>
		<div class="credits float-right">
		    <a href="http://github.com/atelierspierrot/library">atelierspierrot/library</a> package by <a href="https://github.com/atelierspierrot">Les Ateliers Pierrot</a> under <a href="http://opensource.org/licenses/GPL-3.0">GNU GPL v.3</a> license.
		</div>
    </footer>

    <div class="back_menu" id="short_navigation">
        <a href="#" title="See navigation menu" id="short_menu_handler"><span class="text">Navigation Menu</span></a>
        &nbsp;|&nbsp;
        <a href="#top" title="Back to the top of the page"><span class="text">Back to top&nbsp;</span>&uarr;</a>
        <ul id="short_menu" class="menu" role="navigation"></ul>
    </div>

    <div id="message_box" class="msg_box"></div>

<!-- jQuery lib -->
<script src="assets/js/jquery-1.9.1.min.js"></script>

<!-- HTML5 boilerplate -->
<script src="assets/html5boilerplate/js/plugins.js"></script>

<!-- jQuery.highlight plugin -->
<script src="assets/js/highlight.js"></script>

<!-- scripts for demo -->
<script src="assets/scripts.js"></script>

<script>
$(function() {
    initBacklinks();
    activateMenuItem();
    getToHash();
    buildFootNotes();
    addCSSValidatorLink('assets/styles.css');
    addHTMLValidatorLink();
    $("#user_agent").html( navigator.userAgent );
    $('pre.code').highlight({source:0, indent:'tabs', code_lang: 'data-language'});
});
</script>
</body>
</html>
