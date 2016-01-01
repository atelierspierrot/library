<?php
/**
 * This file is part of the Library package.
 *
 * Copyleft (ↄ) 2013-2016 Pierre Cassat <me@e-piwi.fr> and contributors
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


namespace Library\Converter;

/**
 * HTML to plain text converter
 *
 * Freely (but mostly) inspired by the work of Jon Abernathy <jon@chuggnutt.com>
 * and his "class.html2text.inc" (see <http://www.chuggnutt.com/html2text>).
 *
 * @author  piwi <me@e-piwi.fr>
 */
class Html2Text
    extends AbstractConverter
{

    public static $correspondances = array(
        "/\r/"=>'',                                     // Non-legal carriage return
        "/[\n\t]+/"=>' ',                               // Newlines and tabs
        '/[ ]{2,}/'=>' ',                               // Runs of spaces, pre-handling
        '/<script[^>]*>.*?<\/script>/i'=>'',            // <script>s -- which strip_tags supposedly has problems with
        '/<style[^>]*>.*?<\/style>/i'=>'',              // <style>s -- which strip_tags supposedly has problems with
        '/<h[123][^>]*>(.*?)<\/h[123]>/ie'=>"strtoupper(\"\n\n\\1\n\n\")",      // H1 - H3 uppercase
        '/<h[456][^>]*>(.*?)<\/h[456]>/ie'=>"ucwords(\"\n\n\\1\n\n\")",      // H4 - H6 uppercase first car.
        '/<p[^>]*>/i'=>"\n\n\t",                           // <P> are indented
        '/<br[^>]*>/i'=>"\n",                          // <br>
        '/<b[^>]*>(.*?)<\/b>/ie'=>'strtoupper("\\1")',                // <b> uppercase
        '/<strong[^>]*>(.*?)<\/strong>/ie'=>'strtoupper("\\1")',      // <strong> uppercase
        '/<i[^>]*>(.*?)<\/i>/i'=>'_\\1_',                 // <i> surround between underscores
        '/<em[^>]*>(.*?)<\/em>/i'=>'_\\1_',               // <em> surround between underscores
        '/(<ul[^>]*>|<\/ul>)/i'=>"\n\n",                 // <ul> and </ul>
        '/(<ol[^>]*>|<\/ol>)/i'=>"\n\n",                 // <ol> and </ol>
        '/<li[^>]*>(.*?)<\/li>/i'=>"\t* \\1\n",               // <li> and </li>
        '/<li[^>]*>/i'=>"\n\t* ",                          // <li>
        '/<abbr [^>]*title="([^"]+)"[^>]*>(.*?)<\/abbr>/i'=>'\\2 (\\1)', // <abbr title="">
        '/<a [^>]*href="#([^"]+)"[^>]*>(.*?)<\/a>/i'=>'\\2 (cf. \\1)', // <a href="#...">
        '/<a [^>]*href="javascript:[^"]+"[^>]*>(.*?)<\/a>/i'=>'\\1', // <a href="javascript:">
        '/<a [^>]*href="mailto:([^"]+)"[^>]*>(.*?)<\/a>/i'=>'\\2 [@: \\1]', // <a href="javascript:">
        '/<a [^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/i'=>'\\2 [\\1]', // <a href="">
        '/<hr[^>]*>/i'=>"\n-------------------------\n",                          // <hr>
        '/(<table[^>]*>|<\/table>)/i'=>"\n\n",           // <table> and </table>
        '/(<tr[^>]*>|<\/tr>)/i'=>"\n",                 // <tr> and </tr>
        '/<td[^>]*>(.*?)<\/td>/i'=>"\t\t\\1\n",               // <td> and </td>
        '/<th[^>]*>(.*?)<\/th>/ie'=>"strtoupper(\"\t\t\\1\n\")",              // <th> and </th>
        '/&(nbsp|#160);/i'=>' ',                      // Non-breaking space
        '/&(quot|rdquo|ldquo|#8220|#8221|#147|#148);/i'=>'"', // Double quotes
        '/&(apos|rsquo|lsquo|#8216|#8217);/i'=>"'",   // Single quotes
        '/&gt;/i'=>'>',                               // Greater-than
        '/&lt;/i'=>'<',                               // Less-than
        '/&(amp|#38);/i'=>'&',                        // Ampersand
        '/&(copy|#169);/i'=>'(c)',                      // Copyright
        '/&(trade|#8482|#153);/i'=>'(tm)',               // Trademark
        '/&(reg|#174);/i'=>'(R)',                       // Registered
        '/&(mdash|#151|#8212);/i'=>'--',               // mdash
        '/&(ndash|minus|#8211|#8722);/i'=>'-',        // ndash
        '/&(bull|#149|#8226);/i'=>'*',                // Bullet
        '/&(pound|#163);/i'=>'£',                     // Pound sign
        '/&(euro|#8364);/i'=>'€',                     // Euro sign
        '/&(dollar|#036);/i'=>'$',                     // Euro sign
        '/&[^&;]+;/i'=>'',                           // Unknown/unhandled entities
        '/[ ]{2,}/'=>' ',                              // Runs of spaces, post-handling
    );

    public $allowed_tags=array();
    public $line_width;

    public function setAllowedTags($tags)
    {
        $this->allowed_tags = $tags;
    }

    public function setLineWidth($line_width)
    {
        $this->line_width = $line_width;
    }

    public static function convert($content, $allowed_tags = null, $line_width = null)
    {
        $_this = new Html2Text;
        if (!empty($allowed_tags)) {
            $_this->setAllowedTags($allowed_tags);
        }
        if (!empty($line_width)) {
            $_this->setLineWidth($line_width);
        }

        $text = trim(stripslashes($content));

        // Run our defined search-and-replace
        $text = preg_replace(
            array_keys(self::$correspondances),
            array_values(self::$correspondances),
            $text
        );

        // Strip any other HTML tags
        $text = strip_tags($text, join('', $_this->allowed_tags));

        // Bring down number of empty lines to 2 max
        $text = preg_replace("/\n\s+\n/", "\n\n", $text);
        $text = preg_replace("/[\n]{3,}/", "\n\n", $text);

        // Wrap the text to a readable format
        // for PHP versions >= 4.0.2. Default line_width is 75
        // If line_width is 0 or less, don't wrap the text.
        if ( $_this->line_width > 0 ) {
            $text = wordwrap($text, $_this->line_width);
        }

        return $text;
    }

}

