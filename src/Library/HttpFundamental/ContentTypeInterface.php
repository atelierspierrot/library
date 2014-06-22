<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013-2014 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
 */
namespace Library\HttpFundamental;

use \Library\HttpFundamental\Response;

/**
 */
interface ContentTypeInterface
{

    /**
     * Prepare the content of the response before to send it to client
     *
     * @param \Library\HttpFundamental\Response $response
     * @return void
     */
    public function prepareResponse(Response $response);

    /**
     * Parse an input content
     *
     * @param string $content
     * @return string|array
     */
    public function parseContent($content);

    /**
     * Prepare a content for output
     *
     * @param string|array $content
     * @return string
     */
    public function prepareContent($content);

    /**
     * Get the "content-Type" header value
     *
     * @return string
     */
    public static function getContentType();

}

// Endfile
