
First notes
-----------

All these classes works in a PHP version 5.3 minus environment. They are included 
in the *Namespace* **Library**.

For clarity, the examples below are NOT written as a working PHP code when it seems 
not necessary. For example, rather than write `echo "my_string";` we would write
`echo my_string` or rather than `var_export($data);` we would write `echo $data`.
The main code for these classes'usage is written strictly.

As a reminder, and because it's always useful, have a look at the
[PHP common coding standards](http://pear.php.net/manual/<?php echo $arg_ln; ?>/standards.php).

Tests & documentation
---------------------
    
### Include the `Library` namespace

As the package classes names are built following the [PHP Framework Interoperability Group recommandations](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md), 
we use the [SplClassLoader](https://gist.github.com/jwage/221634) to load package classes. 
The loader is included in the package but you can use your own.

```php
require_once ".../src/SplClassLoader.php"; // if required, a copy is proposed in the package
$classLoader = new SplClassLoader("Library", "/path/to/package/src");
$classLoader->register();
<?php
require_once __DIR__."/../../src/SplClassLoader.php";
$classLoader = new SplClassLoader("Library", __DIR__."/../../src");
$classLoader->register();
?>

```

### Library\Helper

The `Helpers` of the package all defines some static methods.

#### Library\Helper\Url

```php
echo Library\Helper\Url::getRequestUrl();
<?php echo '=> '.Library\Helper\Url::getRequestUrl(); ?>

$str = "test";
echo Library\Helper\Url::isUrl($str);
<?php
$str = "test";
echo '=> '.var_export(Library\Helper\Url::isUrl($str),1);
?>

$str2 = "http://google.fr/";
echo Library\Helper\Url::isUrl($str2);
<?php
$str2 = "http://google.fr/";
echo '=> '.var_export(Library\Helper\Url::isUrl($str2),1);
?>

echo $str = "test";
echo Library\Helper\Url::isEmail($str);
<?php
$str = "test";
echo '=> '.var_export(Library\Helper\Url::isEmail($str),1);
?>

$str2 = "mail@google.fr";
echo Library\Helper\Url::isEmail($str2);
<?php
$str2 = "mail@google.fr";
echo '=> '.var_export(Library\Helper\Url::isEmail($str2),1);
?>

$str = "http://google.fr/azerty/../test/string/?url=this-url&q=search";
echo Library\Helper\Url::resolvePath($str);
<?php
$str = "http://google.fr/azerty/../test/string/?url=this-url&q=search";
echo '=> '.var_export(Library\Helper\Url::resolvePath($str),1);
?>

```

<?php $_urlstr = 'http://www.google.fr/aspecialpage/?a=param1&b=param2&c[]=param3-1&c[]=param3-2'; ?>
Working on URL `<?php echo $_urlstr; ?>`:

```php
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

```

#### Library\Helper\Text

```php
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

echo "\n\n";
$specialstr = 'My string with special chars: é à è È Ô';
echo '$str = "My string with special chars: é à è È Ô";'."\n";
echo 'echo Library\Helper\Text::stripSpecialChars($specialstr);'."\n";
echo '=> '.Library\Helper\Text::stripSpecialChars($specialstr)."\n";
echo 'echo Library\Helper\Text::slugify($specialstr);'."\n";
echo '=> '.Library\Helper\Text::slugify($specialstr)."\n";
?>

```

#### Library\Helper\Request

```php
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

```

#### Library\Helper\File

```php
<?php
echo 'echo $str = "My ! special éàè§ text file name";'."\n";
echo 'echo Library\Helper\File::formatFilename($str);'."\n";
$str = "My ! special éàè§ text file name";
echo '=> '.var_export(Library\Helper\File::formatFilename($str),1);
?>

```

#### Library\Helper\Directory

```php
<?php
$logs = array();
$dir = settings('cwd').'/tmp/tmp_tmp';
echo '$logs = array();'."\n";
echo '$dir = __DIR__."/tmp/tmp_tmp";'."\n";

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

```

#### Library\Helper\Code

```php
<?php
interface MyInterface {
    public function mustImplement();
}
class MyClass implements MyInterface {
    public function mustImplement()
    {
        return;
    }
}
class MyChildClass extends MyClass {
    public function myChildMethod()
    {
        return;
    }
}
echo 'interface MyInterface {'."\n"
    ."\t".'public function mustImplement();'."\n"
    .'}'."\n"
    .'class MyClass implements MyInterface {'."\n"
    ."\t".'public function mustImplement()'."\n"
    ."\t".'{'."\n"
    ."\t\t".'return;'."\n"
    ."\t".'}'."\n"
    .'}'."\n"
    .'class MyChildClass extends MyClass {'."\n"
    ."\t".'public function myChildMethod()'."\n"
    ."\t".'{'."\n"
    ."\t\t".'return;'."\n"
    ."\t".'}'."\n"
    .'}'."\n";
echo "\n";
echo 'echo Library\Helper\Code::implementsInterface("MyClass", "MyInterface");'."\n";
echo '=> '.var_export(Library\Helper\Code::implementsInterface('MyClass', 'MyInterface'),1)."\n";
echo 'echo Library\Helper\Code::implementsInterface("MyClass", "UnknownInterface");'."\n";
echo '=> '.var_export(Library\Helper\Code::implementsInterface('MyClass', 'UnknownInterface'),1)."\n";
echo 'echo Library\Helper\Code::implementsInterface("UnknownClass", "MyInterface");'."\n";
echo '=> '.var_export(Library\Helper\Code::implementsInterface('UnknownClass', 'MyInterface'),1)."\n";
echo "\n";
echo 'echo Library\Helper\Code::extendsClass("MyChildClass", "MyClass");'."\n";
echo '=> '.var_export(Library\Helper\Code::extendsClass('MyChildClass', 'MyClass'),1)."\n";
echo 'echo Library\Helper\Code::extendsClass("MyChildClass", "UnknownClass");'."\n";
echo '=> '.var_export(Library\Helper\Code::extendsClass('MyChildClass', 'UnknownClass'),1)."\n";
echo 'echo Library\Helper\Code::extendsClass("UnknownClass", "MyClass");'."\n";
echo '=> '.var_export(Library\Helper\Code::extendsClass('UnknownClass', 'MyClass'),1)."\n";
echo "\n";
$obj = new MyClass;
echo '$obj = new MyClass;'."\n";
echo 'echo Library\Helper\Code::isClassInstance($obj, "MyClass");'."\n";
echo '=> '.var_export(Library\Helper\Code::isClassInstance($obj, 'MyClass'),1)."\n";
echo 'echo Library\Helper\Code::isClassInstance($obj, "MyChildClass");'."\n";
echo '=> '.var_export(Library\Helper\Code::isClassInstance($obj, 'MyChildClass'),1)."\n";
echo 'echo Library\Helper\Code::isClassInstance($obj, "UnknownClass");'."\n";
echo '=> '.var_export(Library\Helper\Code::isClassInstance($obj, 'UnknownClass'),1)."\n";
$no_obj = "my var";
echo '$no_obj = "my var";'."\n";
echo 'echo Library\Helper\Code::isClassInstance($no_obj, "MyClass");'."\n";
echo '=> '.var_export(Library\Helper\Code::isClassInstance($no_obj, 'MyClass'),1)."\n";
echo "\n";
echo 'echo Library\Helper\Code::namespaceExists("Library\Helper");'."\n";
echo '=> '.var_export(Library\Helper\Code::namespaceExists('Library\Helper'),1)."\n";
echo 'echo Library\Helper\Code::namespaceExists("Library\NotExists");'."\n";
echo '=> '.var_export(Library\Helper\Code::namespaceExists('Library\NotExists'),1)."\n";


function MyMethod( $arg_one, $arg_two = 'default 2', $arg_three = 'default 3') 
{
    echo "=> calling ".__FUNCTION__." with arguments ".var_export(func_get_args(),1);
}

class MyTestClass
{
    function MyMethod( $arg_one, $arg_two = 'default 2', $arg_three = 'default 3') 
    {
        echo "=> calling ".__CLASS__."::".__FUNCTION__." with arguments ".var_export(func_get_args(),1);
    }
    function MyMethod2( $arg_one = 'default 1', $arg_two, $arg_three = 'default 3') 
    {
        echo "=> calling ".__CLASS__."::".__FUNCTION__." with arguments ".var_export(func_get_args(),1);
    }
}

echo "\n";
echo 'function MyMethod( $arg_one, $arg_two = "default 2", $arg_three = "default 3")'."\n"
    ."{\n"
    ."\t".'echo "=> calling ".__FUNCTION__." with arguments ".var_export(func_get_args(),1);'."\n"
    ."}\n";
echo "\n";
$rest = array();
echo '$rest = array()'."\n";
echo 'echo Library\Helper\Code::organizeArguments("MyMethod", array("arg_one"=>"test", "arg_three"=>"test B", "arg_four"=>"test"), null, $logs = array());'."\n";
echo '=> '.var_export(Library\Helper\Code::organizeArguments('MyMethod', array('arg_one'=>'test', 'arg_three'=>'test B', 'arg_four'=>'test'), null, $rest),1)."\n";
echo 'echo $rest;'."\n";
echo '=> '.var_export($rest,1)."\n";
echo 'echo Library\Helper\Code::fetchArguments("MyMethod", array("arg_one"=>"test", "arg_three"=>"test B", "arg_four"=>"test"));'."\n";
Library\Helper\Code::fetchArguments('MyMethod', array('arg_one'=>'test', 'arg_three'=>'test B', 'arg_four'=>'test'));
echo "\n";
echo "\n";
echo 'class MyTestClass'."\n"
    .'{'."\n"
    ."\t".'function MyMethod( $arg_one, $arg_two = "default 2", $arg_three = "default 3")'."\n"
    ."\t".'{'."\n"
    ."\t\t".'echo "=> calling ".__CLASS__."::".__FUNCTION__." with arguments ".var_export(func_get_args(),1);'."\n"
    ."\t".'}'."\n"
    ."\t".'function MyMethod2( $arg_one = "default 1", $arg_two, $arg_three = "default 3")'."\n"
    ."\t".'{'."\n"
    ."\t\t".'echo "=> calling ".__CLASS__."::".__FUNCTION__." with arguments ".var_export(func_get_args(),1);'."\n"
    ."\t".'}'."\n"
    .'}'."\n";
echo "\n";
$rest = array();
echo '$rest = array()'."\n";
echo 'echo Library\Helper\Code::organizeArguments("MyMethod", array("arg_one"=>"test", "arg_three"=>"test B", "arg_four"=>"test"), "MyTestClass", $rest);'."\n";
echo '=> '.var_export(Library\Helper\Code::organizeArguments('MyMethod', array('arg_one'=>'test', 'arg_three'=>'test B', 'arg_four'=>'test'), 'MyTestClass', $rest),1)."\n";
echo 'echo $rest;'."\n";
echo '=> '.var_export($rest,1)."\n";
echo 'echo Library\Helper\Code::fetchArguments("MyMethod", array("arg_one"=>"test", "arg_three"=>"test B", "arg_four"=>"test"), "MyTestClass");'."\n";
Library\Helper\Code::fetchArguments('MyMethod', array('arg_one'=>'test', 'arg_three'=>'test B', 'arg_four'=>'test'), 'MyTestClass');
echo "\n";
echo "\n";
$rest = array();
echo '$rest = array()'."\n";
echo 'echo Library\Helper\Code::fetchArguments("MyMethod", array("arg_three"=>"test B", "arg_four"=>"test"), "MyTestClass", $rest);'."\n";
Library\Helper\Code::fetchArguments('MyMethod', array('arg_three'=>'test B', 'arg_four'=>'test'), 'MyTestClass', $rest);
echo "\n";
echo 'echo $rest;'."\n";
echo '=> '.var_export($rest,1)."\n";
echo "\n";
$rest = array();
echo '$rest = array()'."\n";
echo 'echo Library\Helper\Code::fetchArguments("MyMethod", "test", "MyTestClass", $rest);'."\n";
Library\Helper\Code::fetchArguments('MyMethod', 'test', 'MyTestClass', $rest);
echo "\n";
echo 'echo $rest;'."\n";
echo '=> '.var_export($rest,1)."\n";
echo "\n";
$rest = array();
echo '$rest = array()'."\n";
echo 'echo Library\Helper\Code::fetchArguments("MyMethod2", "test", "MyTestClass", $rest);'."\n";
Library\Helper\Code::fetchArguments('MyMethod2', 'test', 'MyTestClass', $rest);
echo "\n";
echo 'echo $rest;'."\n";
echo '=> '.var_export($rest,1)."\n";
?>

```

#### Library\Helper\Number

```php
<?php

echo "echo Library\Helper\Number::isOdd(i)\n";
for ($i=0; $i<11; $i++) {
    echo "i=$i : ".(Library\Helper\Number::isOdd($i) ? "ODD" : "EVEN")."\n";
}

echo "echo Library\Helper\Number::isEven(i)\n";
for ($i=10; $i<21; $i++) {
    echo "i=$i : ".(Library\Helper\Number::isEven($i) ? "EVEN" : "ODD")."\n";
}

echo "echo Library\Helper\Number::isPrime(i)\n";
for ($i=0; $i<50; $i++) {
    echo "i=$i : ".(Library\Helper\Number::isPrime($i) ? "PRIME" : "-")."\n";
}

/*
echo "echo Library\Helper\Number::isPalindromic(i)\n";
for ($i=0; $i<30; $i++) {
    echo "i=$i : ".(Library\Helper\Number::isPalindromic($i) ? "PALINDROME" : "-")."\n";
}
*/

echo "echo Library\Helper\Number::getFibonacciItem(i)\n";
for ($i=0; $i<11; $i++) {
    echo "i=$i : ".Library\Helper\Number::getFibonacciItem($i)."\n";
}

?>

```

### Library\Tool

The `Tools` of the package works around a specific type of data ; they are considered
as `Helpers` but their methods are not static and they works just as a standalone simple class.

#### Library\Tool\Table

```php
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

```

<pre style="width: auto !important">
<?php
echo $table;
?>
</pre>

Same rendering with a padding with empty cells:

```php
<?php
$table->setPadFlag(Library\Tool\Table::PAD_BY_EMPTY_CELLS);
echo '$table->setPadFlag(Library\Tool\Table::PAD_BY_EMPTY_CELLS);'."\n";
echo 'echo $table->render(STR_PAD_BOTH)'."\n";
?>

```

<pre style="overflow-x: visible">
<?php
echo $table->render(STR_PAD_BOTH)."\n";
?>
</pre>

Table manipulation:

```php
<?php
echo '$table->addColumn(array('."\n"
    ."\t".'"first new col val", "second new col val"'."\n"
    .'), "def", "my new col title");'."\n";
echo 'echo $table'."\n";
?>

```

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

```php
<?php
echo '$table->addColumn(array('."\n"
    ."\t".'"first inserted col val", "second inserted col val", 4=>"value for 4"'."\n"
    .'), null, "my inserted col title", null, 2);'."\n";
echo 'echo $table'."\n";
?>

```

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

```php
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

```

### Library\StaticConfiguration\Config

The `StaticConfiguration\Config` class defines a global fully static configuration manager.

```php
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

```

### Library\Command

The `Command` class runs commands on your system.

```php
<?php
echo '$command = new Library\Command;'."\n";
$command = new Library\Command;
echo 'echo $command->run("whoami");'."\n";
echo '=> '.var_export($command->run("whoami"),1);
echo "\n";
echo 'echo $pwd = $command->getCommandPath("pwd");'."\n";
echo '=> '.var_export($pwd = $command->getCommandPath("pwd"),1);
echo "\n";
echo 'echo $command->run($pwd);'."\n";
echo '=> '.var_export($command->run($pwd),1);

?>

```

### Library\Crypt

```php
<?php
echo '$str="what ever";'."\n";
echo '$salt = "g(UmYZ[?25=%Fns8kK}&UrzRGPp?A-^gV}BP@!?c;f,Vl}X(Ob,pZ~=ABSXv_9yZ";'."\n";
echo '$encryptor = new Library\Tool\Encrypt($salt);'."\n";

$str="what ever";
$salt = "g(UmYZ[?25=%Fns8kK}&UrzRGPp?A-^gV}BP@!?c;f,Vl}X(Ob,pZ~=ABSXv_9yZ";
$encryptor = new Library\Tool\Encrypt($salt);

echo "\n";
echo '$crypted = $encryptor->crypt($str);'."\n";
echo 'echo $crypted;'."\n";
$crypted = $encryptor->encrypt($str);
echo '=> '.$crypted."\n";

echo "\n";
echo '$uncrypted = $encryptor->uncrypt($crypted);'."\n";
echo 'echo $uncrypted;'."\n";
$uncrypted = $encryptor->decrypt($crypted);
echo '=> '.$uncrypted."\n";
?>

```

### Library\Reporter

#### HTML adapter

```php
<?php
$reporter = new Library\Reporter\Reporter;
echo '$reporter = new Library\Reporter\Reporter;'."\n";
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

```

HTML rendering of the examples above:

>   <?php

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

/*
echo $reporter->render($errors_full_table_contents, "table", $errors_table_args);
*/
echo $reporter->render($definitions, "definition");
?>

### Library\Object

The `Object` namespace proposes some abstract classes to help construct Model Objects.

#### Library\Object\AbstractInvokable

This class defines some default PHP magic methods to facilitate some object properties accesses ;
for more information, see [PHP5 magic overloading](http://www.php.net/manual/fr/language.oop5.overloading.php)
on the PHP manual.

It builds an homogeneus object in which you can use the following methods to access a property even if it is protected (*the string "Property" must be replaced by the true property name*):

-   `getProperty( $default )` or `$this->property` to get the object `$property` or `$_property` variable; the `$default` value is used if the property exists but is not defined;
-   `setProperty( $value )` or `$this->property = $value` to set the object `$property` or `$_property` variable on `$value`;
-   `issetProperty()` or `isset( $this->property )` or `empty( $this->property )` to test the existence of an object `$property` or `$_property` variable;
-   `unsetProperty()` or `unset( $this->property )` to delete an object `$property` or `$_property` variable;
-   `resetProperty()` or `reset( $this->property )` to reset an object `$property` or `$_property` variable on its default value.

This first example shows the default behavior of PHP on non-accessible object properties:

```php
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

```

This example is the same as above but with the invokable methods:

```php
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

```
Working with object statics:

```php
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

```

Using the object as a function:

```php
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

```

#### Library\Object\RegistryInvokable

```php
<?php
require_once __DIR__.'/../../vendor/autoload.php';

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

```
