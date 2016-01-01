<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/library>.
 */

namespace testsLibrary\Helper;

use \testsLibrary\TestCase;

class UrlHelperTest
    extends TestCase
{

    /**
     * Test if method `\Library\Helper\Url::$meth()` with no argument returns `null` with no error
     *
     * @param $meth
     * @param $default
     */
    public function checkNoArg($meth, $default = null)
    {
        $this->checkHelperMethodNoArg('\Library\Helper\Url', $meth, $default);
    }

    /**
     * @covers ../../../src/Library/Helper/Url::isUrl()
     */
    public function testIsUrl()
    {
        $this->checkNoArg('isUrl');

        // simple domain name: must be ok
        $url = 'http://www.google.com/';
        $this->assertTrue(
            \Library\Helper\Url::isUrl($url),
            sprintf('isUrl fails for "%s"!', $url)
        );

        // simple domain name with HTTPS: must be ok
        $url = 'https://www.google.com/';
        $this->assertTrue(
            \Library\Helper\Url::isUrl($url),
            sprintf('isUrl fails for "%s"!', $url)
        );

        // simple domain name with FTP: must be ok
        $url = 'ftp://www.google.com/';
        $this->assertTrue(
            \Library\Helper\Url::isUrl($url),
            sprintf('isUrl fails for "%s"!', $url)
        );

        // complex url: must be ok
        $url = 'http://www.domain.com/azerty/hui.html?qsdfqsdf';
        $this->assertTrue(
            \Library\Helper\Url::isUrl($url),
            sprintf('isUrl fails for "%s"!', $url)
        );

        // complex url with a hash: must be ko
        $url = 'http://www.domain.com/azerty/hui.html?qsdfqsdf#uioiu';
        $this->assertFalse(
            \Library\Helper\Url::isUrl($url),
            sprintf('isUrl fails for "%s"!', $url)
        );

        // simple string: must be ko
        $url = 'lorem ipsum ? azeerty';
        $this->assertFalse(
            \Library\Helper\Url::isUrl($url),
            sprintf('isUrl fails for "%s"!', $url)
        );

        // simple string with all url characters: must be ko
        $url = 'http lorem :// ipsum ? azeerty ? ppp';
        $this->assertFalse(
            \Library\Helper\Url::isUrl($url),
            sprintf('isUrl fails for "%s"!', $url)
        );

        // complex url with a space: must be ko
        $url = 'http://www.domain .com/azerty/hui.html?qsdfqsdf';
        $this->assertFalse(
            \Library\Helper\Url::isUrl($url),
            sprintf('isUrl fails for "%s"!', $url)
        );

        // simple domain name with FILE: must be ko
        $url = 'file://www.google.com/';
        $this->assertFalse(
            \Library\Helper\Url::isUrl($url),
            sprintf('isUrl fails for "%s"!', $url)
        );

        // simple domain name with FILE passing arg 'file': must be ko
        $url = 'file://www.google.com/';
        $this->assertTrue(
            \Library\Helper\Url::isUrl($url, array('file')),
            sprintf('isUrl fails for "%s"!', $url)
        );
    }

    /**
     * @covers ../../../src/Library/Helper/Url::isEmail()
     */
    public function testIsEmail()
    {
        $this->checkNoArg('isEmail');

        // simple email: must be ok
        $url = 'name@domain.com';
        $this->assertTrue(
            \Library\Helper\Url::isEmail($url),
            sprintf('isEmail fails for "%s"!', $url)
        );

        // simple email with subdomain: must be ok
        $url = 'name@subdomain.domain.com';
        $this->assertTrue(
            \Library\Helper\Url::isEmail($url),
            sprintf('isEmail fails for "%s"!', $url)
        );

        // simple email with no extension: must be ko
        $url = 'name@domain';
        $this->assertFalse(
            \Library\Helper\Url::isEmail($url),
            sprintf('isEmail fails for "%s"!', $url)
        );

        // simple email with no name: must be ko
        $url = '@domain.com';
        $this->assertFalse(
            \Library\Helper\Url::isEmail($url),
            sprintf('isEmail fails for "%s"!', $url)
        );

        // simple email with no @: must be ko
        $url = 'name.domain.com';
        $this->assertFalse(
            \Library\Helper\Url::isEmail($url),
            sprintf('isEmail fails for "%s"!', $url)
        );

        // simple string: must be ko
        $url = 'lorem ipsum';
        $this->assertFalse(
            \Library\Helper\Url::isEmail($url),
            sprintf('isEmail fails for "%s"!', $url)
        );

        // simple string with @: must be ko
        $url = 'lorem @ ipsum.com';
        $this->assertFalse(
            \Library\Helper\Url::isEmail($url),
            sprintf('isEmail fails for "%s"!', $url)
        );
    }

    /**
     * @covers ../../../src/Library/Helper/Url::getParameter()
     */
    public function testParams()
    {
        $this->checkNoArg('getParameter');
        $url = 'http://domain.com/index.php?param=A&param1=test1&param2=test2';
        $this->assertEquals('test1', \Library\Helper\Url::getParameter('param1', $url));
        $url_t = str_replace('test2', 'azerty', $url);
        $this->assertEquals($url_t, \Library\Helper\Url::setParameter('param2', 'azerty', $url));
    }

    /**
     * @covers ../../../src/Library/Helper/Url::resolveHttp()
     * @covers ../../../src/Library/Helper/Url::resolvePath()
     */
    public function testResolvers()
    {
        $this->checkNoArg('resolveHttp');
        $url = 'domain.com/index.php?param=A&param1=test1&param2=test2';
        $this->assertEquals('http://'.$url, \Library\Helper\Url::resolveHttp($url));
        $this->assertEquals('http://'.$url, \Library\Helper\Url::resolveHttp('http://'.$url));

        $this->checkNoArg('resolvePath');
        $path       = 'my/path/../to/./a/file';
        $realpath   = 'my/to/a/file';
        $this->assertEquals($realpath, \Library\Helper\Url::resolvePath($path));
    }

    /**
     * @covers ../../../src/Library/Helper/Url::isIpAddress()
     */
    public function testIsIpAddress()
    {
        $this->checkNoArg('isIpAddress', false);

        $ip     = '123.165.187.230';
        $ipl    = '127.0.0.1';
        $ipf    = '123.765.987.2toto';
        $ip_v6  = '2001:610:240:22::c100:68b';

        $this->assertFalse(\Library\Helper\Url::isIpAddress($ipf));
        $this->assertFalse(\Library\Helper\Url::isIpAddress($ipf, false, false));
        $this->assertFalse(\Library\Helper\Url::isIpAddress($ipf, true, false));
        $this->assertFalse(\Library\Helper\Url::isIpAddress($ipf, false, true));

        $this->assertTrue(\Library\Helper\Url::isIpAddress($ipl));

        $this->assertTrue(\Library\Helper\Url::isIpAddress($ip));
        $this->assertTrue(\Library\Helper\Url::isIpAddress($ip, true, false));
        $this->assertFalse(\Library\Helper\Url::isIpAddress($ip, false, true));
        $this->assertFalse(\Library\Helper\Url::isIpAddress($ip, false, false));

        $this->assertTrue(\Library\Helper\Url::isIpAddress($ip_v6));
        $this->assertTrue(\Library\Helper\Url::isIpAddress($ip_v6, false, true));
        $this->assertFalse(\Library\Helper\Url::isIpAddress($ip_v6, true, false));
        $this->assertFalse(\Library\Helper\Url::isIpAddress($ip_v6, false, false));
    }
}
