<?php

// show errors at least initially
@ini_set('display_errors','1'); @error_reporting(E_ALL ^ E_NOTICE);

// set a default timezone to avoid PHP5 warnings
$dtmz = date_default_timezone_get();
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
            <li><a href="index.php">Homepage</a><ul>
                <li><a href="index.php#helpers">Helpers</a><ul>
                    <li><a href="index.php#urlhelper">Url</a></li>
                    <li><a href="index.php#texthelper">Text</a></li>
                    <li><a href="index.php#requesthelper">Request</a></li>
                    <li><a href="index.php#filehelper">File</a></li>
                </ul></li>
                <li><a href="index.php#command">Command</a></li>
                <li><a href="index.php#crypt">Crypt</a></li>
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
    <p>All these classes works in a PHP version 5.3 minus environment. They are included in the <em>Namespace</em> <strong>Library</strong>.</p>
    <p>For clarity, the examples below are NOT written as a working PHP code when it seems not necessary. For example, rather than write <var>echo "my_string";</var> we would write <var>echo my_string</var> or rather than <var>var_export($data);</var> we would write <var>echo $data</var>. The main code for these classes'usage is written strictly.</p>
    <p>As a reminder, and because it's always useful, have a look at the <a href="http://pear.php.net/manual/<?php echo $arg_ln; ?>/standards.php">PHP common coding standards</a>.</p>

	<h2 id="tests">Tests & documentation</h2>
    
<h3>Include the <var>Library</var> namespace</h3>

    <p>As the package classes names are built following the <a href="https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md">PHP Framework Interoperability Group recommandations</a>, we use the <a href="https://gist.github.com/jwage/221634">SplClassLoader</a> to load package classes. The loader is included in the package but you can use your own.</p>

    <pre class="code" data-language="php">
<?php
echo 'require_once ".../src/SplClassLoader.php"; // if required, a copy is proposed in the package'."\n";
echo '$classLoader = new SplClassLoader("Library", "/path/to/package/src");'."\n";
echo '$classLoader->register();';

require_once __DIR__."/../src/SplClassLoader.php";
$classLoader = new SplClassLoader("Library", __DIR__."/../src");
$classLoader->register();
?>
    </pre>

<h3 id="helpers">Library\Helper</h3>

    <p>The <var>Helpers</var> of the package all defines some static methods.</p>

<h4 id="urlhelper">Library\Helper\Url</h4>
    <pre class="code" data-language="php">
<?php
echo 'echo Library\Helper\Url::getRequestUrl();'."\n";
echo '=> '.Library\Helper\Url::getRequestUrl();

echo "\n\n";
echo 'echo $str = "test";'."\n";
echo 'echo Library\Helper\Url::isUrl($str);'."\n";
$str = "test";
echo '=> '.var_export(Library\Helper\Url::isUrl($str),1);
echo "\n";
echo 'echo $str2 = "http://google.fr/";'."\n";
echo 'echo Library\Helper\Url::isUrl($str2);'."\n";
$str2 = "http://google.fr/";
echo '=> '.var_export(Library\Helper\Url::isUrl($str2),1);

echo "\n\n";
echo 'echo $str = "test";'."\n";
echo 'echo Library\Helper\Url::isEmail($str);'."\n";
$str = "test";
echo '=> '.var_export(Library\Helper\Url::isEmail($str),1);
echo "\n";
echo 'echo $str2 = "mail@google.fr";'."\n";
echo 'echo Library\Helper\Url::isEmail($str2);'."\n";
$str2 = "mail@google.fr";
echo '=> '.var_export(Library\Helper\Url::isEmail($str2),1);

echo "\n\n";
echo 'echo $str = "http://google.fr/azerty/../test/string/?url=this-url&q=search";'."\n";
echo 'echo Library\Helper\Url::resolvePath($str);'."\n";
$str = "http://google.fr/azerty/../test/string/?url=this-url&q=search";
echo '=> '.var_export(Library\Helper\Url::resolvePath($str),1);
?>
    </pre>

<?php $_urlstr = 'http://www.google.fr/aspecialpage/?a=param1&b=param2&c[]=param3-1&c[]=param3-2'; ?>
<p>Working on URL <var><?php echo $_urlstr; ?></var>:</p>

    <pre class="code" data-language="php">
<?php
echo 'echo Library\Helper\Url::getParameter("a", $_urlstr);'."\n";
echo '=> '.Library\Helper\Url::getParameter("a", $_urlstr);
echo "\n\n";
echo 'echo Library\Helper\Url::getParameter("b", $_urlstr);'."\n";
echo '=> '.Library\Helper\Url::getParameter("b", $_urlstr);
echo "\n\n";
echo 'echo Library\Helper\Url::getParameter("c", $_urlstr);'."\n";
echo '=> '.var_export(Library\Helper\Url::getParameter("c", $_urlstr),1);
echo "\n\n";
echo 'echo Library\Helper\Url::setParameter("a", "newval", $_urlstr);'."\n";
echo '=> '.urldecode(Library\Helper\Url::setParameter("a", "newval", $_urlstr));
echo "\n\n";
echo 'echo Library\Helper\Url::setParameter("a", array("newval-1", "newval-2"), $_urlstr);'."\n";
echo '=> '.urldecode(Library\Helper\Url::setParameter("a", array("newval-1", "newval-2"), $_urlstr));
?>
    </pre>

<h4 id="texthelper">Library\Helper\Text</h4>
    <pre class="code" data-language="php">
<?php
echo 'echo $str = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";'."\n";
echo 'echo Library\Helper\Text::cut($str, 32);'."\n";
$str = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
echo '=> '.Library\Helper\Text::cut($str, 32);
?>
    </pre>

<h4 id="requesthelper">Library\Helper\Request</h4>
    <pre class="code" data-language="php">
<?php
echo 'echo Library\Helper\Request::isCli();'."\n";
echo '=> '.var_export(Library\Helper\Request::isCli(),1);

echo "\n\n";
echo 'echo Library\Helper\Request::isAjax();'."\n";
echo '=> '.var_export(Library\Helper\Request::isCli(),1);

echo "\n\n";
echo 'echo Library\Helper\Request::getUserIp();'."\n";
echo '=> '.var_export(Library\Helper\Request::getUserIp(),1);
?>
    </pre>

<h4 id="filehelper">Library\Helper\File</h4>
    <pre class="code" data-language="php">
<?php
echo 'echo $str = "My ! special éàè§ text file name";'."\n";
echo 'echo Library\Helper\File::formatFilename($str);'."\n";
$str = "My ! special éàè§ text file name";
echo '=> '.var_export(Library\Helper\File::formatFilename($str),1);
?>
    </pre>

<h3 id="command">Library\Command</h3>

    <p>The <var>Command</var> class runs commands on your system.</p>

    <pre class="code" data-language="php">
<?php
echo '$command = new Library\Command;'."\n";
echo 'echo $command->run("whoami");'."\n";
$command = new Library\Command;
echo '=> '.var_export($command->run("whoami"),1);
echo "\n";
echo 'echo $command->run("pwd");'."\n";
echo '=> '.var_export($command->run("pwd"),1);

?>
    </pre>

<h3 id="crypt">Library\Crypt</h3>

    <pre class="code" data-language="php">
<?php
echo '$str="what ever";'."\n";
echo '$salt = "g(UmYZ[?25=%Fns8kK}&UrzRGPp?A-^gV}BP@!?c;f,Vl}X(Ob,pZ~=ABSXv_9yZ";'."\n";
echo '$encryptor = new Library\Crypt($salt);'."\n";

$str="what ever";
$salt = "g(UmYZ[?25=%Fns8kK}&UrzRGPp?A-^gV}BP@!?c;f,Vl}X(Ob,pZ~=ABSXv_9yZ";
$encryptor = new Library\Crypt($salt);

echo "\n";
echo '$crypted = $encryptor->crypt($str);'."\n";
echo 'echo $crypted;'."\n";
$crypted = $encryptor->crypt($str);
echo '=> '.$crypted."\n";

echo "\n";
echo '$uncrypted = $encryptor->uncrypt($crypted);'."\n";
echo 'echo $uncrypted;'."\n";
$uncrypted = $encryptor->uncrypt($crypted);
echo '=> '.$uncrypted."\n";
?>
    </pre>

        </article>
    </div>

    <footer id="footer">
		<div class="credits float-left">
		    This page is <a href="" title="Check now online" id="html_validation">HTML5</a> & <a href="" title="Check now online" id="css_validation">CSS3</a> valid.
		</div>
		<div class="credits float-right">
		    <a href="https://github.com/atelierspierrot/internationalization">atelierspierrot/internationalization</a> package by <a href="https://github.com/PieroWbmstr">Piero Wbmstr</a> under <a href="http://opensource.org/licenses/GPL-3.0">GNU GPL v.3</a> license.
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
