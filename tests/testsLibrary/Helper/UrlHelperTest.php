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

    public function testIsUrl()
    {
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

    public function testIsEmail()
    {
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

}
