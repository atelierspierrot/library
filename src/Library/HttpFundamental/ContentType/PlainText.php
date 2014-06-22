<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */
namespace Library\HttpFundamental\ContentType;

use \Library\HttpFundamental\ContentTypeInterface;
use \Library\HttpFundamental\Response;
use \Library\Converter\Html2Text;

/**
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class PlainText
    implements ContentTypeInterface
{

    /**
     * Prepare the content of the response before to send it to client
     *
     * @param \Library\HttpFundamental\Response $response
     * @return void
     */
    public function prepareResponse(Response $response)
    {
    }

    /**
     * Parse an input content
     *
     * @param string $content
     * @return mixed
     */
    public function parseContent($content)
    {
        return (string) $content;
    }

    /**
     * Prepare a content for output
     *
     * @param mixed $content
     * @return string
     */
    public function prepareContent($content)
    {
        if (is_array($content)) {
            $ctt = '';
            foreach ($content as $key=>$ctt) {
                $content .= $ctt;
            }
            $content = $ctt;
        }
        $_escaped_output = strip_tags((string) $content);
        if ($_escaped_output != (string) $content) {
            if (preg_match('/(.*)<body(.*)</body>/i', (string) $content, $matches)) {
                $_output = $matches[0];
            } else {
                $_output = (string) $content;
            }
            $content = Html2Text::convert($_output);
        }
        return (string) $content;
    }

    /**
     * Get the "content-Type" header value
     *
     * @return string
     */
    public static function getContentType()
    {
        return 'text/plain';
    }

}

// Endfile
