<?php
/**
 * This file is part of the Library package.
 *
 * Copyright (c) 2013-2015 Pierre Cassat <me@e-piwi.fr> and contributors
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/library>.
 */

namespace testsLibrary\Helper;

use \testsLibrary\TestCase;

class TextHelperTest
    extends TestCase
{

    /**
     * Test if method `\Library\Helper\Text::$meth()` with no argument returns `null` with no error
     *
     * @param $meth
     * @param $default
     */
    public function checkNoArg($meth, $default = '')
    {
        $this->checkHelperMethodNoArg('\Library\Helper\Text', $meth, $default);
    }

    /**
     * @covers ../../../src/Library/Helper/Text::cut()
     */
    public function testCut()
    {
        $this->checkNoArg('cut');
        $this->assertEquals('Lorem ...', \Library\Helper\Text::cut($this->lorem_ipsum, 6));
        $this->assertEquals('Lo ...', \Library\Helper\Text::cut($this->lorem_ipsum, 2));
        $this->assertEquals('Lo', \Library\Helper\Text::cut($this->lorem_ipsum, 2, ''));
        $this->assertEquals($this->lorem_ipsum, \Library\Helper\Text::cut($this->lorem_ipsum, strlen($this->lorem_ipsum)+1));
    }

    /**
     * @covers ../../../src/Library/Helper/Text::wrap()
     */
    public function testWrap()
    {
        $this->checkNoArg('wrap');
        $wraped =  \Library\Helper\Text::wrap($this->lorem_ipsum);
        $this->assertEquals($this->lorem_ipsum, str_replace("\n", ' ', $wraped));
    }

    /**
     * @covers ../../../src/Library/Helper/Text::slugify()
     */
    public function testSlugify()
    {
        $this->checkNoArg('slugify');
        $str = 'Lorem é ipsum à amet [§!*] lorem';
        $str_a = 'lorem-e-ipsum-a-amet-lorem';
        $this->assertEquals($str_a, \Library\Helper\Text::slugify($str));
    }

    /**
     * @covers ../../../src/Library/Helper/Text::getHumanReadable()
     */
    public function test_getHumanReadable()
    {
        $this->checkNoArg('getHumanReadable');
        $str = 'lorem ipsum amet lorem';
        $str_a = 'lorem_ipsum.amet/lorem';
        $this->assertEquals($str, \Library\Helper\Text::getHumanReadable($str_a));
    }

    /**
     * @covers ../../../src/Library/Helper/Text::stripSpecialChars()
     */
    public function testStripSpecialChars()
    {
        $this->checkNoArg('stripSpecialChars');
        $str = 'Lorem é ipsum à amet §!* lorem';
        $str_a = 'Loremeipsumaametlorem';
        $str_b = 'Lorem e ipsum a amet  lorem';
        $this->assertEquals($str_a, \Library\Helper\Text::stripSpecialChars($str));
        $this->assertEquals($str_b, \Library\Helper\Text::stripSpecialChars($str, ' '));
    }

    /**
     * @covers ../../../src/Library/Helper/Text::toCamelCase()
     * @covers ../../../src/Library/Helper/Text::fromCamelCase()
     */
    public function testCamelCase()
    {
        $this->checkNoArg('toCamelCase');
        $this->assertEquals('MyCamelCasePhrase', \Library\Helper\Text::toCamelCase('my_camel_case_phrase'));
        $this->checkNoArg('fromCamelCase');
        $this->assertEquals('my_camel_case_phrase', \Library\Helper\Text::fromCamelCase('MyCamelCasePhrase'));
    }

}
