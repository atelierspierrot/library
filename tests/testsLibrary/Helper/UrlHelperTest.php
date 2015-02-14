<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
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

}
