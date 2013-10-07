<?php

// show errors at least initially
@ini_set('display_errors','1'); @error_reporting(E_ALL ^ E_NOTICE);

// set a default timezone to avoid PHP5 warnings
$dtmz = @date_default_timezone_get();
date_default_timezone_set( !empty($dtmz) ? $dtmz:'Europe/Paris' );

// for security
function _getSecuredRealPath( $str )
{
    $parts = explode('/', realpath('.'));
    array_pop($parts);
    array_pop($parts);
    return str_replace(join('/', $parts), '/[***]', $str);
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
            <li><a href="http_fundamental.php">HTTP Fundamental</a><ul>
                <li><a href="http_fundamental.php#contentype">Content-types</a></li>
                <li><a href="http_fundamental.php#request">Request</a></li>
                <li><a href="http_fundamental.php#response">Response</a></li>
            </ul></li>
            <li><a href="dev.php">Dev branch</a></li>
        </ul>

        <div class="info">
            <p><a href="https://github.com/atelierspierrot/library">See online on GitHub</a></p>
            <p class="comment">The sources of this plugin are hosted on <a href="http://github.com">GitHub</a>. To follow sources updates, report a bug or read opened bug tickets and any other information, please see the GitHub website above.</p>
        </div>

    	<p class="credits" id="user_agent"></p>
	</nav>

    <div id="content" role="main">

        <article>

	<h2 id="tests">Tests & documentation</h2>

<?php
require_once __DIR__."/../src/SplClassLoader.php";
$classLoader = new SplClassLoader("Library", __DIR__."/../src");
$classLoader->register();
?>
    
<h3 id="contentype">Library\HttpFundamental\ContentType</h3>

    <pre class="code" data-language="php">
<?php

$contents = array(
    'first' => 'my first content',
    'second' => 'my 2nd content',
    3 => 'my third content (original ;)',
);

echo "\n";
echo '$contents = array('."\n"
    ."\t".'"first" => "my first content",'."\n"
    ."\t".'"second" => "my 2nd content",'."\n"
    ."\t".'3 => "my third content (original ;)",'."\n"
    .');'."\n";

// get parsed content in JSON
echo "\n";
$content_type_json = new Library\HttpFundamental\ContentType("json");
echo '$content_type_json = new Library\HttpFundamental\ContentType("json");'."\n";
$ctt_json = $content_type_json->prepareContent($contents);
echo '$ctt_json = $content_type_json->prepareContent($contents)'."\n";
echo '// => '.var_export($ctt_json,1)."\n";

// get parsed content in HTML
echo "\n";
$content_type_html = new Library\HttpFundamental\ContentType("html");
echo '$content_type_html = new Library\HttpFundamental\ContentType("html");'."\n";
$ctt_html = $content_type_html->prepareContent($contents);
echo '$ctt_html = $content_type_html->prepareContent($contents)'."\n";
echo '// => '.var_export($ctt_html,1)."\n";

?>
    </pre>

<h3 id="request">Library\HttpFundamental\Request</h3>

<p>You can play with this object adding GET arguments to current URL.</p>

    <pre class="code" data-language="php">
<?php
$request = new Library\HttpFundamental\Request;
echo '$request = new Library\HttpFundamental\Request;'."\n";
echo var_export($request,1)."\n";
?>
    </pre>

<h3 id="response">Library\HttpFundamental\Response</h3>

<p>TODO : documentation ...</p>

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
