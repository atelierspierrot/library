
First notes
-----------

This page requires you install the Composer dependencies in `dev` mode.

Tests & documentation
---------------------

<?php
require_once __DIR__."/../../src/SplClassLoader.php";
$classLoader = new SplClassLoader("Library", __DIR__."/../src");
$classLoader->register();
?>
    
### Library\Logger

For this demo, log files will be created in "demo/tmp/" directory ; if it does not exist, 
please create it with a chmod of at least 755.

```php
$classLoader = new SplClassLoader("Psr\Log", __DIR__."/../vendor/psr/log");
$classLoader->register();
<?php
$classLoader = new SplClassLoader("Psr\Log", __DIR__."/../vendor/psr/log");
$classLoader->register();
?>

$log_options = array(
    "directory" => __DIR__."/tmp",
);
$logger = new Library\Logger($log_options);
<?php
$log_options = array(
    'directory' => __DIR__.'/tmp'
);
$logger = new Library\Logger($log_options);
?>

// write a simple log
$ok = $logger->log(Library\Logger::DEBUG, "my message");
<?php
$ok = $logger->log(Library\Logger::DEBUG, 'my message');
echo '// => '.var_export($ok,1)."\n";
?>

// write a log message with placeholders
$ok = $logger->log(Library\Logger::DEBUG, "my message with placeholders : {one} and {two}", array(
    "one" => "my value for first placeholder",
    "two" => new TestClass( "my test class with a toString method" )
));
<?php
class TestClass
{
    var $msg;
    function __construct( $str ){
        $this->msg = $str;
    }
    function __toString(){
        return $this->msg;
    }
}
$ok = $logger->log(Library\Logger::DEBUG, "my message with placeholders : {one} and {two}", array(
    'one' => 'my value for first placeholder',
    'two' => new TestClass( 'my test class with a toString method' )
));
echo '// => '.var_export($ok,1)."\n";
?>

// write logs in a specific "test" file
$ok = $logger->log(Library\Logger::DEBUG, "my message", array(), "test");
<?php
$ok = $logger->log(Library\Logger::DEBUG, 'my message', array(), 'test');
echo '// => '.var_export($ok,1)."\n";
?>

$ok = $logger->log( Library\Logger::DEBUG, "[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf " );
$ok = $logger->log( Library\Logger::ERROR, "a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf " );
$ok = $logger->log( Library\Logger::INFO, "a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ", $_GET, "test" );
<?php
$ok = $logger->log( Library\Logger::DEBUG, '[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
$ok = $logger->log( Library\Logger::ERROR, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
$ok = $logger->log( Library\Logger::INFO, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ', $_GET, 'test' );
echo '// => '.var_export($ok,1)."\n";
?>

// write many logs to test rotation
for ($i=0; $i<1000; $i++) {
    $ok = $logger->log( Library\Logger::DEBUG, '[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
    $ok = $logger->log( Library\Logger::ERROR, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
    $ok = $logger->log( Library\Logger::INFO, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ', $_GET, 'test' );
}
<?php
for ($i=0; $i<1000; $i++){
    $ok = $logger->log( Library\Logger::DEBUG, '[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
    $ok = $logger->log( Library\Logger::ERROR, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ' );
    $ok = $logger->log( Library\Logger::INFO, 'a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf ', $_GET, 'test' );
}
?>

```

### Library\FileRotator

For this demo, files will be created in "demo/tmp/" directory ; if it does not exist, 
please create it with a chmod of at least 755.

```php
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
for ($i=0; $i<1000; $i++) {
    $ok = $rotator->write('[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
}
sleep(70);
for ($i=0; $i<1000; $i++) {
    $ok = $rotator->write('[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
}
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
/*
for ($i=0; $i<1000; $i++) {
    $ok = $rotator->write('[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
}
sleep(70);
for ($i=0; $i<1000; $i++) {
    $ok = $rotator->write('[from ?] a simple message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
    $ok = $rotator->write('a long message qsmldkf jfqksmldkfjqmlskdf jmlqksjmdlfkj jKMlkjqmlsdkjf');
}
*/
?>

```
