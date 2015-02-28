
Tests & documentation
---------------------

<?php
if (file_exists($_f = __DIR__."/../../vendor/autoload.php")) {
    require_once $_f;
} else {
    trigger_error('You need to run Composer on your package to install dependencies!', E_USER_ERROR);
}
?>
    
### Library\HttpFundamental\ContentType

```php
$contents = array(
    'first' => 'my first content',
    'second' => 'my 2nd content',
    3 => 'my third content (original ;)',
);
<?php
$contents = array(
    'first' => 'my first content',
    'second' => 'my 2nd content',
    3 => 'my third content (original ;)',
);
?>

// get parsed content in JSON
$content_type_json = new Library\HttpFundamental\ContentType("json");
$ctt_json = $content_type_json->prepareContent($contents);
var_export($ctt_json);
<?php
$content_type_json = new Library\HttpFundamental\ContentType("json");
$ctt_json = $content_type_json->prepareContent($contents);
echo '// => '.var_export($ctt_json,1)."\n";
?>

// get parsed content in HTML
$content_type_html = new Library\HttpFundamental\ContentType("html");
$ctt_html = $content_type_html->prepareContent($contents);
var_export($ctt_html);
<?php
$content_type_html = new Library\HttpFundamental\ContentType("html");
$ctt_html = $content_type_html->prepareContent($contents);
echo '// => '.var_export($ctt_html,1)."\n";
?>

```

### Library\HttpFundamental\Request

#### Current request

The object below is populated analyzing the current request made to view this page.

You can play with this object adding arguments or data to current URL. Some tools can help 
you to do so, such as the [Tamper Data](http://addons.mozilla.org/fr/firefox/addon/tamper-data/) 
extension for [Firefox](http://www.mozilla.org/en-US/firefox/fx/).

```php
$request = new Library\HttpFundamental\Request;
<?php
$request = new Library\HttpFundamental\Request;
echo var_export($request,1)."\n";
?>

```

#### Custom request

```php
$url = Library\Helper\Url::getRequestUrl(false, true);
$flag = Library\HttpFundamental\Request::NO_REWRITE;
$protocol = 'http';
$method = 'get';
$headers = null;
$get = array(
    'get_arg1'=>'value 1',
    'get_arg2'=>'value 2',
);
$post = array(
    'post_arg1'=>'value 1',
    'post_arg2'=>'value 2',
);
$session = null;
$files = null;
$cookies = null;
$custom_request = Library\HttpFundamental\Request::create(
    $url, $flag, $protocol, $method, $headers, $get, $post, $session, $files, $cookies
);
<?php
/*
    public static function create(
        $url = null, $flag = self::NO_REWRITE,
        $protocol = 'http', $method = 'get', array $headers = null, 
        array $arguments = null, array $data = null, 
        array $session = null, array $files = null, array $cookies = null
    ) {
*/
$url = Library\Helper\Url::getRequestUrl(false, true);
$flag = Library\HttpFundamental\Request::NO_REWRITE;
$protocol = 'http';
$method = 'get';
$headers = null;
$get = array(
    'get_arg1'=>'value 1',
    'get_arg2'=>'value 2',
);
$post = array(
    'post_arg1'=>'value 1',
    'post_arg2'=>'value 2',
);
$session = null;
$files = null;
$cookies = null;
$custom_request = Library\HttpFundamental\Request::create(
    $url, $flag, $protocol, $method, $headers, $get, $post, $session, $files, $cookies
);
?>

var_export($custom_request);
<?php echo var_export($custom_request,1)."\n"; ?>

echo $custom_request->buildUrl();
<?php echo '// '.$custom_request->buildUrl()."\n"; ?>

$custom_request->setFlag(Library\HttpFundamental\Request::REWRITE_SEGMENTS_QUERY);
$custom_request->setAuthenticationUser('anonymous');
<?php 
$custom_request->setFlag(Library\HttpFundamental\Request::REWRITE_SEGMENTS_QUERY); 
$custom_request->setAuthenticationUser('anonymous');
?>
echo $custom_request->buildUrl();
<?php echo '// '.$custom_request->buildUrl()."\n"; ?>

```

### Library\HttpFundamental\Response

```php
$response = new \Library\HttpFundamental\Response();
<?php
$response = new \Library\HttpFundamental\Response();
var_export($response);
?>

```

TODO : documentation ...
