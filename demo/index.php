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
                    <li><a href="index.php#dirhelper">Directory</a></li>
                    <li><a href="index.php#codehelper">Code</a></li>
                </ul></li>
                <li><a href="index.php#tools">Tools</a><ul>
                    <li><a href="index.php#tabletool">Table</a></li>
                </ul></li>
                <li><a href="index.php#config">Config</a></li>
                <li><a href="index.php#command">Command</a></li>
                <li><a href="index.php#crypt">Crypt</a></li>
                <li><a href="index.php#reporter">Reporter</a></li>
                <li><a href="index.php#objects">Objects</a><ul>
                    <li><a href="index.php#invokable">Invokable</a></li>
                    <li><a href="index.php#registryinvokable">Registry Invokable</a></li>
                </ul></li>
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

echo "\n\n";
$str = 'my_underscored_nameCap';
echo '$str = "my_underscored_nameCap";'."\n";
echo 'echo Library\Helper\Text::toCamelCase($str);'."\n";
echo '=> '.Library\Helper\Text::toCamelCase($str)."\n";
echo 'echo Library\Helper\Text::toCamelCase($str, "_", false);'."\n";
echo '=> '.Library\Helper\Text::toCamelCase($str, '_', false)."\n";

echo "\n\n";
$str = 'my/path/nameCap';
echo '$str = "my/path/nameCap";'."\n";
echo 'echo Library\Helper\Text::toCamelCase($str, "/");'."\n";
echo '=> '.Library\Helper\Text::toCamelCase($str, '/')."\n";
echo 'echo Library\Helper\Text::toCamelCase($str, "/", false);'."\n";
echo '=> '.Library\Helper\Text::toCamelCase($str, '/', false)."\n";

echo "\n\n";
$str = 'MyCamelCaseString';
echo '$str = "MyCamelCaseString";'."\n";
echo 'echo Library\Helper\Text::fromCamelCase($str);'."\n";
echo '=> '.Library\Helper\Text::fromCamelCase($str)."\n";
echo 'echo Library\Helper\Text::fromCamelCase($str, "_", false);'."\n";
echo '=> '.Library\Helper\Text::fromCamelCase($str, '_', false)."\n";
echo 'echo Library\Helper\Text::fromCamelCase($str, "/");'."\n";
echo '=> '.Library\Helper\Text::fromCamelCase($str, '/')."\n";
echo 'echo Library\Helper\Text::fromCamelCase($str, "/", false);'."\n";
echo '=> '.Library\Helper\Text::fromCamelCase($str, '/', false)."\n";

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

<h4 id="dirhelper">Library\Helper\Directory</h4>
    <pre class="code" data-language="php">
<?php
$logs = array();
$dir = __DIR__.'/tmp_tmp';
echo '$logs = array();'."\n";
echo '$dir = __DIR__."/tmp_tmp";'."\n";

\Library\Helper\Directory::ensureExists($dir);
\Library\Helper\File::touch($dir.'/test1');
\Library\Helper\File::touch($dir.'/test2');
\Library\Helper\File::touch($dir.'/test/test1');
\Library\Helper\File::touch($dir.'/test/test2');
\Library\Helper\Directory::chmod($dir, 777, true, 766, $logs);
\Library\Helper\Directory::remove($dir, $logs);
echo "\n";
echo '\Library\Helper\Directory::ensureExists($dir);'."\n";
echo '\Library\Helper\File::touch($dir."/test1");'."\n";
echo '\Library\Helper\File::touch($dir."/test2");'."\n";
echo '\Library\Helper\File::touch($dir."/test/test1");'."\n";
echo '\Library\Helper\File::touch($dir."/test/test2");'."\n";
echo '\Library\Helper\Directory::chmod($dir, 777, true, 766, $logs);'."\n";
echo '\Library\Helper\Directory::remove($dir, $logs);'."\n";
echo "\n";
echo 'var_export($logs);'."\n";
var_export($logs);

?>
    </pre>

<h4 id="codehelper">Library\Helper\Code</h4>
    <pre class="code" data-language="php">
<?php
interface MyInterface {
    public function MustImplement();
}
class MyClass implements MyInterface {
    public function MustImplement()
    {
        return;
    }
}
echo 'interface MyInterface {'."\n"
    ."\t".'public function MustImplement();'."\n"
    .'}'."\n"
    .'class MyClass implements MyInterface {'."\n"
    ."\t".'public function MustImplement()'."\n"
    ."\t".'{'."\n"
    ."\t\t".'return;'."\n"
    ."\t".'}'."\n"
    .'}'."\n";
echo "\n";
echo 'echo Library\Helper\Code::impelementsInterface("MyClass", "MyInterface");'."\n";
echo '=> '.var_export(Library\Helper\Code::impelementsInterface('MyClass', 'MyInterface'),1)."\n";
echo 'echo Library\Helper\Code::impelementsInterface("MyClass", "UnknownInterface");'."\n";
echo '=> '.var_export(Library\Helper\Code::impelementsInterface('MyClass', 'UnknownInterface'),1)."\n";
?>
    </pre>

<h3 id="tools">Library\Tool</h3>

    <p>The <var>Tools</var> of the package works around a specific type of data ; they are considered as <var>Helpers</var> but their methods are not static and they works just as a standalone simple class.</p>

<h4 id="tabletool">Library\Tool\Table</h4>

    <pre class="code" data-language="php">
<?php
$table_contents = array(
    0=>array('first item', 'second item', 'third item'),
    1=>array('first item of second line', 'second item of second line', 'third item of second line')
);
$table_headers = array(
    'first table header', 'second header', 'header'
);
$table = new Library\Tool\Table($table_contents, $table_headers, array(), null, Library\Tool\Table::PAD_BY_SPAN);
$table->setTitle('My table title');
$table->addLine(array('a new line with', 'only two entries'));
$table->addLine('a new line with only one entry');
$table->addLine(array('a new line with', 'more entries', 'than before', 'to test repadding'));

echo '$table_contents = array('."\n"
    ."\t".'0=>array("first item', 'second item", "third item"),'."\n"
    ."\t".'1=>array("first item of second line", "second item of second line", "third item of second line")'."\n"
    .');'."\n";
echo '$table_headers = array('."\n"
    ."\t".'"first table header", "second header", "header"'."\n"
    .');'."\n";
echo '$table = new Library\Tool\Table($table_contents, $table_headers, array(), null, Library\Tool\Table::PAD_BY_SPAN);'."\n";
echo '$table->setTitle("My table title");'."\n";
echo '$table->addLine(array("a new line with", "only two entries"));'."\n";
echo '$table->addLine("a new line with only one entry");'."\n";
echo '$table->addLine(array("a new line with", "more entries", "than before", "to test repadding"));'."\n";
echo "\n";
echo 'echo $table->getTable()'."\n";
var_export($table->getTable());
?>
    </pre>

<pre style="width: auto !important">
<?php
echo $table;
?>
</pre>

    <p>Same rendering with a padding with empty cells:</p>

    <pre class="code" data-language="php">
<?php
$table->setPadFlag(Library\Tool\Table::PAD_BY_EMPTY_CELLS);
echo '$table->setPadFlag(Library\Tool\Table::PAD_BY_EMPTY_CELLS);'."\n";
echo 'echo $table->render(STR_PAD_BOTH)'."\n";
?>
    </pre>

<pre style="overflow-x: visible">
<?php
echo $table->render(STR_PAD_BOTH)."\n";
?>
</pre>

    <p>Table manipulation:</p>

    <pre class="code" data-language="php">
<?php
echo '$table->addColumn(array('."\n"
    ."\t".'"first new col val", "second new col val"'."\n"
    .'), "def", "my new col title");'."\n";
echo 'echo $table'."\n";
?>
</pre>

<div style="overflow-x: scroll">
<pre style="width: 3000px !important">
<?php
$table->addColumn(array(
    'first new col val', 'second new col val'
), 'def', 'my new col title');
echo $table."\n";
?>
</pre>
</div>

    <pre class="code" data-language="php">
<?php
echo '$table->addColumn(array('."\n"
    ."\t".'"first inserted col val", "second inserted col val", 4=>"value for 4"'."\n"
    .'), null, "my inserted col title", null, 2);'."\n";
echo 'echo $table'."\n";
?>
</pre>
</div>

<div style="overflow-x: scroll">
<pre style="width: 3000px !important">
<?php
$table->addColumn(array(
    'first inserted col val', 'second inserted col val', 4=>'value for 4'
), null, 'my inserted col title', array(), 2);
echo $table."\n";
?>
</pre>
</div>

    <pre class="code" data-language="php">
<?php
echo '// get a cell content'."\n";
echo '$table->getCell(2,2)'."\n";
var_export($table->getCell(2,2));
echo "\n";
echo '// get a line content'."\n";
echo '$table->getLine(1)'."\n";
var_export($table->getLine(1));
echo "\n";
echo '// get a column content'."\n";
echo '$table->getColumn(2)'."\n";
var_export($table->getColumn(2));
echo "\n";
echo '// get an iterator over table body'."\n";
echo '$table->getTableIterator()'."\n";
var_export($table->getTableIterator());
echo "\n";
echo '// get an iterator over the whole table, sorted by columns'."\n";
echo '$table->getTableIterator(null, Library\Tool\Table::ITERATE_ON_COLUMNS)'."\n";
var_export($table->getTableIterator(null, Library\Tool\Table::ITERATE_ON_COLUMNS));
?>
</pre>

<h3 id="config">Library\StaticConfiguration\Config</h3>

    <p>The <var>StaticConfiguration\Config</var> class defines a global fully static configuration manager.</p>

    <pre class="code" data-language="php">
<?php
class DefaultConfig implements \Library\StaticConfiguration\ConfiguratorInterface {
    public static function getDefaults() {
        return array(
            'entry1' => array( 'library-assets' ),
            'entry2' => 'test',
            'entry3' => 'other test',
        );
    }
    public static function getRequired() {
        return array('entry1', 'entry2');
    }
}
\Library\StaticConfiguration\Config::load('DefaultConfig');

echo 'class DefaultConfig implements \Library\StaticConfiguration\ConfiguratorInterface {'."\n"
    ."\t".'public static function getDefaults() {'."\n"
    ."\t\t".'return array('."\n"
    ."\t\t\t".'"entry1" => array( "library-assets" ),'."\n"
    ."\t\t\t".'"entry2" => "test",'."\n"
    ."\t\t\t".'"entry3" => "other test",'."\n"
    ."\t\t".');'."\n"
    ."\t".'}'."\n"
    ."\t".'public static function getRequired() {'."\n"
    ."\t\t".'return array("entry1", "entry2");'."\n"
    ."\t".'}'."\n"
    .'}'."\n"
    .'\Library\StaticConfiguration\Config::load("DefaultConfig");'."\n";
echo "\n";
echo 'echo \Library\StaticConfiguration\Config::get("entry1"))'."\n";
echo '=> '.var_export(\Library\StaticConfiguration\Config::get('entry1'),1)."\n";
echo "\n";
echo '\Library\StaticConfiguration\Config::set("entry2", "my value")'."\n";
echo 'echo \Library\StaticConfiguration\Config::get("entry2"))'."\n";
\Library\StaticConfiguration\Config::set('entry2', 'my value');
echo '=> '.var_export(\Library\StaticConfiguration\Config::get('entry2'),1)."\n";
echo 'echo \Library\StaticConfiguration\Config::getDefault("entry2"))'."\n";
echo '=> '.var_export(\Library\StaticConfiguration\Config::getDefault('entry2'),1)."\n";
echo "\n";
echo '\Library\StaticConfiguration\Config::set("entry4", "does not exist")'."\n";
echo 'echo \Library\StaticConfiguration\Config::get("entry4"))'."\n";
\Library\StaticConfiguration\Config::set('entry4', 'does not exist');
echo '=> '.var_export(\Library\StaticConfiguration\Config::get('entry4'),1)."\n";
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

<h3 id="reporter">Library\Reporter</h3>

<h4>HTML adapter</h4>

    <pre class="code" data-language="php">
<?php
$reporter = new Library\Reporter;
echo '$reporter = new Library\Reporter;'."\n";
echo "\n";
echo 'echo $reporter->render("my content");'."\n";
echo '// => '.$reporter->render("my content")."\n";
echo "\n";
echo '$reporter->getAdapter()->newLine();'."\n";
echo '// => '.$reporter->getAdapter()->newLine()."\n";
echo "\n";
echo 'echo $reporter->render("paragraph content", "paragraph");'."\n";
echo '// => '.$reporter->render("paragraph content", "paragraph")."\n";
echo "\n";
echo 'echo $reporter->render("paragraph content", "paragraph", array("attrs"=>array("class"=>"myclass")));'."\n";
echo '// => '.$reporter->render("paragraph content", "paragraph", array("attrs"=>array("class"=>"myclass")))."\n";
echo "\n";
echo 'echo $reporter->render("http://www.google.fr/", "link");'."\n";
echo '// => '.$reporter->render("http://www.google.fr/", "link")."\n";
echo "\n";
echo 'echo $reporter->render("my title", "title");'."\n";
echo '// => '.$reporter->render("my title", "title")."\n";
echo "\n";
echo 'echo $reporter->render("my title", "title", array(3));'."\n";
echo '// => '.$reporter->render("my title", "title", array(3))."\n";
echo 'echo $reporter->render("my title", "title", 3);'."\n";
echo '// => '.$reporter->render("my title", "title", 3)."\n";

echo "\n";
echo '$bold = $reporter->render("some bold text", "bold");'."\n";
echo '$italic = $reporter->render("and some emphasis text", "italic");'."\n";
echo 'echo $reporter->render("In this text, there is $bold $italic for demonstration");'."\n";
$bold = $reporter->render("some bold text", "bold");
$italic = $reporter->render("and some emphasis text", "italic");
echo '// => '.$reporter->render("In this text, there is $bold $italic for demonstration")."\n";

echo "\n";
echo '$reporter->renderMulti("In this text, there is @bold@ @italic@ for demonstration", "default", array('."\n"
    ."\t".'"bold" => array("some bold text", "bold"),'."\n"
    ."\t".'"italic" => array("and some emphasis text", "italic"),'."\n"
    .'))'."\n";
echo '// => '.$reporter->renderMulti("In this text, there is @bold@ @italic@ for demonstration", 'default', array(
    'bold' => array("some bold text", "bold"),
    'italic' => array("and some emphasis text", "italic"),
))."\n";

echo "\n";
$list_items = array(
    'first item',
    $reporter->render("second item (bold)", "bold"),
    'third item',
);
echo '$list_items = array('."\n"
    ."\t".'"first item",'."\n"
    ."\t".'$reporter->render("second item (bold)", "bold"),'."\n"
    ."\t".'"third item",'."\n"
    .');'."\n";
echo 'echo $reporter->render($list_items, "list");'."\n";
echo '// => '.$reporter->render($list_items, "list")."\n";
echo "\n";
echo 'echo $reporter->render($list_items, "list", array('."\n"
    ."\t".'"attrs"=>array("class"=>"myclass")'."\n"
    ."\t".'"items"=>array("attrs"=>array("class"=>"myclass_for_items"))'."\n"
    ."\t".'"item3"=>array("attrs"=>array("class"=>"special_class_for_item_3"))'."\n"
    .'));'."\n";
echo '// => '.$reporter->render($list_items, "list", array(
    "attrs"=>array("class"=>"myclass"),
    "items"=>array("attrs"=>array("class"=>"myclass_for_items")),
    "item2"=>array("attrs"=>array("class"=>"special_class_for_item_3"))
))."\n";

echo "\n";
echo 'echo $reporter->render($list_items, "ordered_list");'."\n";
echo '// => '.$reporter->render($list_items, "ordered_list")."\n";


$table_contents = array(
    array(
        'first cell of first line',
        $reporter->render("second cell (bold)", "bold"),
        'third cell of first line',
    ),
    array(
        'first cell of second line',
        $reporter->render("second cell (italic)", "italic"),
        'third cell of second line',
    )
);
echo "\n";
echo '$table_contents = array('."\n"
    ."\t".'array('."\n"
    ."\t\t".'"first cell of first line",'."\n"
    ."\t\t".'$reporter->render("second cell (bold)", "bold"),'."\n"
    ."\t\t".'"third cell of first line",'."\n"
    ."\t".'),'."\n"
    ."\t".'array('."\n"
    ."\t\t".'"first cell of second line",'."\n"
    ."\t\t".'$reporter->render("second cell (italic)", "italic"),'."\n"
    ."\t\t".'"third cell of second line",'."\n"
    ."\t".')'."\n"
    .');'."\n";
echo 'echo $reporter->render($table_contents, "table");'."\n";
echo '// => '.$reporter->render($table_contents, "table")."\n";

echo "\n";
$full_table_contents = array(
    "title"=>"My table title",
    "head"=>array(
        'First header', 'Second header', 'Third header'
    ),
    "body"=>$table_contents
);
echo '$full_table_contents = array('."\n"
    ."\t".'"title"=>"My table title",'."\n"
    ."\t".'"head"=>array('."\n"
    ."\t\t".'"First header", "Second header", "Third header"'."\n"
    ."\t".'),'."\n"
    ."\t".'"body"=>$table_contents'."\n"
    .');'."\n";
echo 'echo $reporter->render($full_table_contents, "table")'."\n";
echo '// => '.$reporter->render($full_table_contents, "table")."\n";

echo "\n";
$table_args = array(
    "attrs" => array( "cellpadding"=>12, "border"=>1 ),
    "line" => array(
        "attrs" => array( "bgcolor"=>"#ccc" )
    ),
    "cell" => array(
        "head" => array(
            "attrs" => array( "style"=>"color:green" )
        ),
    ),
);
echo '$table_args = array('."\n"
    ."\t".'"attrs" => array( "cellpadding"=>12, "border"=>1 ),'."\n"
    ."\t".'"line" => array('."\n"
        ."\t\t".'"attrs" => array( "bgcolor"=>"#ccc" )'."\n"
    ."\t".'),'."\n"
    ."\t".'"cell" => array('."\n"
        ."\t\t".'"head" => array('."\n"
            ."\t\t\t".'"attrs" => array( "style"=>"color:green" )'."\n"
        ."\t\t".'),'."\n"
    ."\t".'),'."\n"
    .');'."\n";
echo 'echo $reporter->render($full_table_contents, "table", $table_args)'."\n";
echo '// => '.$reporter->render($full_table_contents, "table", $table_args)."\n";
/*
// table with "errors"
$errors_full_table_contents = array(
    "head"=>array(
        'First header', 'Second header', 'Third header'
    ),
    "body"=> array(
        array(
            'first cell of first line',
            $reporter->render("second cell (bold)", "bold"),
            'third cell of first line',
        ),
        array(
            'first cell of second line',
            $reporter->render("second cell (italic)", "italic"),
        ),
        array(
            'first cell of third line',
            'second cell of third line',
            'third cell of third line',
        )
    )
);
$errors_table_args = array(
    "attrs" => array( "cellpadding"=>12, "border"=>1 ),
);
echo 'echo $reporter->render($errors_full_table_contents, "table", $errors_table_args)'."\n";
echo '// => '.$reporter->render($errors_full_table_contents, "table", $errors_table_args)."\n";
*/
echo "\n";
$definitions = array(
    "term 1" => "My definition of term 1",
    "term 2" => "My definition of term 2",
    "term 3 (after 2)" => "My definition of term 3",
);
echo 'echo $reporter->render($definitions, "definition")'."\n";
echo '// => '.$reporter->render($definitions, "definition")."\n";


?>
    </pre>

    <p>HTML rendering of the examples above:</p>
<blockquote>
<?php

echo $reporter->render("my content");

echo $reporter->render("paragraph content", "paragraph");

echo $reporter->render("paragraph content", "paragraph", array("attrs"=>array("class"=>"myclass")));

echo $reporter->render("http://www.google.fr/", "link");
echo $reporter->getAdapter()->newLine();

echo $reporter->render("my title", "title");

echo $reporter->render("my title", "title", array(3));

echo $reporter->render("my title", "title", 3);

echo $reporter->render("In this text, there is $bold $italic for demonstration");

echo $reporter->renderMulti("In this text, there is @bold@ @italic@ for demonstration", 'default', array(
    'bold' => array("some bold text", "bold"),
    'italic' => array("and some emphasis text", "italic"),
));

echo $reporter->render($list_items, "list");

echo $reporter->render($list_items, "list", array(
    "attrs"=>array("class"=>"myclass"),
    "items"=>array("attrs"=>array("class"=>"myclass_for_items")),
    "item2"=>array("attrs"=>array("class"=>"special_class_for_item_3"))
));

echo $reporter->render($list_items, "ordered_list");

echo $reporter->render($table_contents, "table");

echo $reporter->render($full_table_contents, "table");

echo $reporter->render($full_table_contents, "table", $table_args);

echo $reporter->render($errors_full_table_contents, "table", $errors_table_args);

echo $reporter->render($definitions, "definition");
?>
</blockquote>

<h3 id="objects">Library\Object</h3>

    <p>The <var>Object</var> namespace proposes some abstract classes to help construct Model Objects.</p>

<h4 id="invokable">Library\Object\AbstractInvokable</h4>

    <p>This class defines some default PHP magic methods to facilitate some object properties accesses ; for more informations, see <a href="http://www.php.net/manual/fr/language.oop5.overloading.php">PHP5 magic overloading</a> on the PHP manual.
    It builds an homogeneus object in which you can use the following methods to access a property even if it is protected (<em>the string "Property" must be replaced by the true property name</em>):</p>
    <ul>
        <li><var>getProperty( $default )</var> or <var>$this->property</var> to get the object <var>$property</var> or <var>$_property</var> variable; the <var>$default</var> value is used if the property exists but is not defined;</li>
        <li><var>setProperty( $value )</var> or <var>$this->property = $value</var> to set the object <var>$property</var> or <var>$_property</var> variable on <var>$value</var>;</li>
        <li><var>issetProperty()</var> or <var>isset( $this->property )</var> or <var>empty( $this->property )</var> to test the existence of an object <var>$property</var> or <var>$_property</var> variable;</li>
        <li><var>unsetProperty()</var> or <var>unset( $this->property )</var> to delete an object <var>$property</var> or <var>$_property</var> variable;</li>
        <li><var>resetProperty()</var> or <var>reset( $this->property )</var> to reset an object <var>$property</var> or <var>$_property</var> variable on its default value.</li>
    </ul>

    <p>This first example shows the default behavior of PHP on non-accessible object properties:</p>

    <pre class="code" data-language="php">
class Mytest
{

    // public property
    public $my_public_prop = 10;

    // protected property
    protected $_my_protected_prop = 20;

    // private property
    private $__my_private_prop = 30;

    public function __construct($a, $b, $c)
    {
        $this->my_public_prop = $a;
        $this->_my_protected_prop = $b;
        $this->__my_private_prop = $c;
    }

}
<?php

class Mytest
{

    // public property
    public $my_public_prop = 10;

    // protected property
    protected $_my_protected_prop = 20;

    // private property
    private $__my_private_prop = 30;

    public function __construct($a, $b, $c)
    {
        $this->my_public_prop = $a;
        $this->_my_protected_prop = $b;
        $this->__my_private_prop = $c;
    }

}

$mytest = new Mytest(100, 110, 120);

echo "\n";
echo '$mytest = new Mytest(100, 110, 120);'."\n";
echo '$mytest->my_public_prop;'."\n";
echo '// => 100'."\n";
echo '$mytest->_my_protected_prop;'."\n";
echo '// => Fatal error:  Cannot access protected property Mytest::$_my_protected_prop'."\n";
echo '$mytest->__my_private_prop;'."\n";
echo '// => Fatal error:  Cannot access private property Mytest::$__my_private_prop'."\n";
?>
    </pre>

    <p>This example is the same as above but with the invokable methods:</p>

    <pre class="code" data-language="php">
class MytestInvokable extends Library\Objact\AbstractInvokable
{

    // public property
    public $my_public_prop = 10;

    // protected property
    protected $_my_protected_prop = 20;

    // private property
    private $__my_private_prop = 30;

    public function __construct($a=1, $b=2, $c=3)
    {
        $this->my_public_prop = $a;
        $this->_my_protected_prop = $b;
        $this->__my_private_prop = $c;
    }

    // static property
    static $my_static = 'test';

    // protected static property
    protected static $my_protected_static = 'protected static value';

    // private static property
    private static $my_private_static = 'private static value';

}
<?php

class MytestInvokable extends Library\Object\AbstractInvokable
{

    // public property
    public $my_public_prop = 10;

    // protected property
    protected $_my_protected_prop = 20;

    // private property
    private $__my_private_prop = 30;

    public function __construct($a=1, $b=2, $c=3)
    {
        $this->my_public_prop = $a;
        $this->_my_protected_prop = $b;
        $this->__my_private_prop = $c;
    }

    // public static property
    static $my_static = 'public static value';

    // protected static property
    protected static $my_protected_static = 'protected static value';

    // private static property
    private static $my_private_static = 'private static value';

}

$mytest_invok = new MytestInvokable(100, 110, 120);

echo "\n";
var_dump($mytest_invok);

echo "\n";
echo '$mytest_invok = new MytestInvokable(100, 110, 120);'."\n";
echo '$mytest_invok->my_public_prop;'."\n";
echo '// => '.$mytest_invok->my_public_prop."\n";
echo '$mytest_invok->_my_protected_prop;'."\n";
echo '// => ', $mytest_invok->_my_protected_prop, 'empty because no direct access to protected property', "\n";
echo '$mytest_invok->getMyProtectedProp();'."\n";
echo '// => ', $mytest_invok->getMyProtectedProp(), ' (magic getter is ok)', "\n";
echo '$mytest_invok->__my_private_prop;'."\n";
echo '$mytest_invok->getMyPrivateProp();'."\n";
echo '// => ', $mytest_invok->__my_private_prop, $mytest_invok->getMyPrivateProp(), 'both are empty because the mother class can\'t access private property'."\n";

echo "\n";
echo '$mytest_invok->my_public_prop = 200;'."\n";
echo '$mytest_invok->my_public_prop;'."\n";
$mytest_invok->my_public_prop = 200;
echo '// => '.$mytest_invok->my_public_prop."\n";

echo '$mytest_invok->_my_protected_prop = 210;'."\n";
echo '$mytest_invok->getMyProtectedProp();'."\n";
$mytest_invok->_my_protected_prop = 210;
echo '// => ', $mytest_invok->getMyProtectedProp(), ' (can not set directly as it is protected)', "\n";
echo '$mytest_invok->setMyProtectedProp(210);'."\n";
echo '$mytest_invok->getMyProtectedProp();'."\n";
$mytest_invok->setMyProtectedProp(210);
echo '// => ', $mytest_invok->getMyProtectedProp(), ' (magic setter is ok)', "\n";

echo '$mytest_invok->__my_private_prop = 220;'."\n";
echo '$mytest_invok->getMyPrivateProp();'."\n";
$mytest_invok->__my_private_prop = 220;
echo '$mytest_invok->setMyPrivateProp(220);'."\n";
echo '$mytest_invok->getMyPrivateProp();'."\n";
$mytest_invok->setMyPrivateProp(220);
echo '// => ', $mytest_invok->getMyPrivateProp(), 'both are empty because the mother class can\'t access private property'."\n";

echo "\n";
echo "// => as we can see, '__my_private_prop' has not been set because it is not accessible by the mother class\n";
var_dump($mytest_invok);

echo "\n";
echo 'echo $mytest_invok->getMyPublicProp();'."\n";
echo '// => ', $mytest_invok->getMyPublicProp(), "\n";
echo 'echo $mytest_invok->getMyProtectedProp();'."\n";
echo '// => ', $mytest_invok->getMyProtectedProp(), "\n";
echo 'echo $mytest_invok->getMyPrivateProp();'."\n";
echo '// => empty because the mother class can\'t access private property'."\n";

echo "\n";
echo '$mytest_invok->setMyPublicProp(300);'."\n";
echo '$mytest_invok->getMyPublicProp();'."\n";
$mytest_invok->setMyPublicProp(300);
echo '// => ', $mytest_invok->getMyPublicProp(), "\n";

echo '$mytest_invok->setMyProtectedProp(310);'."\n";
echo 'echo $mytest_invok->getMyProtectedProp();'."\n";
$mytest_invok->setMyProtectedProp(310);
echo '// => ', $mytest_invok->getMyProtectedProp(), "\n";

echo '$mytest_invok->setMyPrivateProp(320);'."\n";
echo '$mytest_invok->getMyPrivateProp();'."\n";
$mytest_invok->setMyPrivateProp(320);
echo '// => empty because the mother class can\'t access private property'."\n";

echo "\n";
echo "// => as we can see, '__my_private_prop' has not been set because it is not accessible by the mother class\n";
var_dump($mytest_invok);

echo "\n";
echo 'isset($mytest_invok->my_public_prop);'."\n";
echo '// => ', var_export(isset($mytest_invok->my_public_prop),1), "\n";
echo '$mytest_invok->issetMyPublicProp();'."\n";
echo '// => ', var_export($mytest_invok->issetMyPublicProp(),1), "\n";

echo 'isset($mytest_invok->_my_protected_prop);'."\n";
echo '// => ', var_export(isset($mytest_invok->_my_protected_prop),1), "\n";
echo '$mytest_invok->issetMyProtectedProp();'."\n";
echo '// => ', var_export($mytest_invok->issetMyProtectedProp(),1), "\n";

echo 'isset($mytest_invok->__my_private_prop);'."\n";
echo '// => ', var_export(isset($mytest_invok->__my_private_prop),1), "\n";
echo '$mytest_invok->issetMyPrivateProp();'."\n";
echo '// => ', var_export($mytest_invok->issetMyPrivateProp(),1), "\n";

echo 'isset($mytest_invok->non_existing_prop);'."\n";
echo '// => ', var_export(isset($mytest_invok->non_existing_prop),1), "\n";
echo '$mytest_invok->issetNonExistingProp();'."\n";
echo '// => ', var_export($mytest_invok->issetNonExistingProp(),1), "\n";

echo "\n";
echo '$mytest_invok->unsetMyPublicProp();'."\n";
$mytest_invok->unsetMyPublicProp();
var_dump($mytest_invok);

echo "\n";
echo '$mytest_invok->resetMyProtectedProp();'."\n";
$mytest_invok->resetMyProtectedProp();
var_dump($mytest_invok);

?>
    </pre>
    <p>Working with object statics:</p>
    <pre class="code" data-language="php">
<?php
echo "\n";
echo 'echo MytestInvokable::$my_static'."\n";
echo ' // => '.MytestInvokable::$my_static."\n";
echo 'echo MytestInvokable::getMyStatic()'."\n";
echo ' // => '.MytestInvokable::getMyStatic()."\n";
echo "\n";
echo 'echo MytestInvokable::$my_protected_static'."\n";
echo ' // => Cannot access protected property MytestInvokable::$my_protected_static'."\n";
echo 'echo MytestInvokable::getMyProtectedStatic()'."\n";
echo ' // => '.MytestInvokable::getMyProtectedStatic()."\n";
echo "\n";
echo 'echo MytestInvokable::$my_private_static'."\n";
echo ' // => Cannot access private property MytestInvokable::$my_private_static'."\n";
echo 'echo MytestInvokable::getMyPrivateStatic()'."\n";
echo ' // => '.MytestInvokable::getMyPrivateStatic()."\n";
echo "\n";
echo 'MytestInvokable::$my_static = "test"'."\n";
MytestInvokable::$my_static = "test";
echo 'echo MytestInvokable::$my_static'."\n";
echo ' // => '.MytestInvokable::$my_static."\n";
echo 'echo MytestInvokable::getMyStatic()'."\n";
echo ' // => '.MytestInvokable::getMyStatic()."\n";
echo "\n";
echo 'MytestInvokable::setMyProtectedStatic("test")'."\n";
MytestInvokable::setMyProtectedStatic("test");
echo 'echo MytestInvokable::getMyProtectedStatic()'."\n";
echo ' // => '.MytestInvokable::getMyProtectedStatic()."\n";
echo "\n";
echo 'MytestInvokable::unsetMyStatic()'."\n";
MytestInvokable::unsetMyStatic();
echo 'echo MytestInvokable::getMyStatic()'."\n";
echo ' // => '.MytestInvokable::getMyStatic().' (empty because the static is not defined any more)'."\n";
echo "\n";
echo 'MytestInvokable::resetMyProtectedStatic()'."\n";
MytestInvokable::resetMyProtectedStatic();
echo 'echo MytestInvokable::getMyProtectedStatic()'."\n";
echo ' // => '.MytestInvokable::getMyProtectedStatic().' (empty because the statics can not be reset but are unset instead)'."\n";
?>
    </pre>

    <p>Using the object as a function:</p>
    <pre class="code" data-language="php">
    This feature doesn't work for now ...
<?php
/*
echo "\n";
echo '$invok = new MytestInvokable(100, 110, 120);'."\n";
echo 'echo $invok("my_public_prop");'."\n";
var_export($invok('my_public_prop'));
echo '// => '.$invok("my_public_prop")."\n";
echo 'echo $invok("myPublicProp");'."\n";
echo '// => '.$invok("myPublicProp")."\n";
echo 'echo $invok("myPublic_prop");'."\n";
echo '// => '.$invok("myPublic_prop")."\n";
*/
?>
    </pre>

<h4 id="registryinvokable">Library\Object\RegistryInvokable</h4>

    <pre class="code" data-language="php">
<?php
require_once __DIR__.'/../vendor/autoload.php';

echo '$myregistry = new Library\Object\RegistryInvokable(null, Library\Object\RegistryInvokable::PUBLIC_PROPERTIES);'."\n";
$myregistry = new Library\Object\RegistryInvokable(null, Library\Object\RegistryInvokable::PUBLIC_PROPERTIES);

echo "\n";
echo '$myregistry->varone = "value one";'."\n";
$myregistry->varone = 'value one';
echo '$myregistry->setVartwo("value two");'."\n";
$myregistry->setVartwo('value two');
echo '$myregistry->var_three = "value three";'."\n";
$myregistry->var_three = 'value three';
echo '$myregistry->setData("var_four", "value four");'."\n";
$myregistry->setData('var_four', 'value four');
echo '$myregistry->setData("myVar five", "value five");'."\n";
$myregistry->setData('myVar five', 'value five');
echo '$myregistry->myVarSix = "value six";'."\n";
$myregistry->myVarSix = 'value six';

echo "\n";
echo 'echo $myregistry->getVarone()'."\n";
echo '// => '.$myregistry->getVarone()."\n";
echo 'echo $myregistry->vartwo'."\n";
echo '// => ', $myregistry->vartwo, "\n";
echo 'echo $myregistry->getVarthree()'."\n";
echo '// => ', $myregistry->getVarthree(), '(empty because var name in magic methods is CamelCasized)', "\n";
echo 'echo $myregistry->getVarThree()'."\n";
echo '// => ', $myregistry->getVarThree(), "\n";
echo 'echo $myregistry->getData("var_four")'."\n";
echo '// => ', $myregistry->getData('var_four'), "\n";
echo 'echo $myregistry->getMyVar_five()'."\n";
echo '// => ', $myregistry->getMyVar_five(), '(empty because var name is lowercasized & underscored)', "\n";
echo 'echo $myregistry->getData("myVar five") (may be empty because var name is lowercasized & underscored)'."\n";
echo '// => ', $myregistry->getData('myVar five'), '(not empty because var name is automatically transformed)', "\n";
echo 'echo $myregistry->getData("my_var_five")'."\n";
echo '// => ', $myregistry->getData('my_var_five'), "\n";
echo 'echo $myregistry->getData("my_var_six")'."\n";
echo '// => ', $myregistry->getData('my_var_six'), "\n";

echo "\n";
var_dump($myregistry);

echo "\n";
echo "\n";
$data = array(
    'one' => 'value one',
    'two' => 'value two',
);
echo '$data = array('."\n"
    .'"one" => "value one",'."\n"
    .'"two" => "value two",'."\n"
.");"."\n";
echo '$mynewregistry = new Library\Object\RegistryInvokable( $data, Library\Object\RegistryInvokable::PUBLIC_PROPERTIES );'."\n";
$mynewregistry = new Library\Object\RegistryInvokable( $data, Library\Object\RegistryInvokable::PUBLIC_PROPERTIES );

echo "\n";
echo 'echo isset($mynewregistry->one)'."\n";
echo '// => '.var_export(isset($mynewregistry->one),1)."\n";
echo 'echo isset($mynewregistry->three)'."\n";
echo '// => '.var_export(isset($mynewregistry->three),1)."\n";

echo "\n";
echo 'echo isset($mynewregistry->two)'."\n";
echo '// => '.var_export(isset($mynewregistry->two),1)."\n";
echo 'unset($mynewregistry->two)'."\n";
unset($mynewregistry->two);
echo 'echo isset($mynewregistry->two)'."\n";
echo '// => '.var_export(isset($mynewregistry->two),1)."\n";

echo "\n";
echo "\n";
echo '$myerrorregistry = new Library\Object\RegistryInvokable();'."\n";
$myerrorregistry = new Library\Object\RegistryInvokable();
$myerrorregistry->one = "value";
echo '$myerrorregistry->one = "value"'."\n";
echo 'echo $myerrorregistry->one'."\n";
echo '// => '.var_export($myerrorregistry->one,1).' (null because direct access to properties is not allowed)'."\n";
echo 'echo $myerrorregistry->getOne()'."\n";
echo '// => '.var_export($myerrorregistry->getOne(),1).' (magic getter acces is ok)'."\n";

echo "\n";
echo "\n";
echo '$myerrorregistry2 = new Library\Object\RegistryInvokable(null, Library\Object\RegistryInvokable::UNAUTHORIZED_PROPERTIES);'."\n";
$myerrorregistry2 = new Library\Object\RegistryInvokable(null, Library\Object\RegistryInvokable::UNAUTHORIZED_PROPERTIES);
$myerrorregistry2->one = "value";
echo '$myerrorregistry2->one = "value"'."\n";
echo 'echo $myerrorregistry2->one'."\n";
//echo $myerrorregistry2->one."\n";
echo '// => Uncaught exception "Library\Object\InvokableAccessException" with message "Direct access to property "one" on object "Library\Object\RegistryInvokable" is not allowed!"'."\n";
echo 'echo $myerrorregistry2->getOne()'."\n";
echo '// => '.var_export($myerrorregistry2->getOne(),1).' (magic getter acces is ok)'."\n";

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
