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
namespace Library\HttpFundamental;

use \Library\HttpFundamental\ContentTypeInterface;
use \Library\HttpFundamental\Response;
use \Library\Helper\Text as TextHelper;

/**
 * @author      Piero Wbmstr <me@e-piwi.fr>
 */
class ContentType
{

    /**
     * @var string
     */
    protected $content_type;

    /**
     * @var object implementing the `\Library\HttpFundamental\ContentTypeInterface`
     */
    protected $content_type_object;

    /**
     */
    static $content_types = array(
        'html' => 'text/html',
        'text' => 'text/plain',
        'css' => 'text/css',
        'xml' => 'application/xml',
        'javascript' => 'application/x-javascript',
        'json' => 'application/json',
    );

    /**
     * Create a new ContentType object extracting the type from a content string
     *
     * @param string $content
     * @return self
     */
    public static function createFromContent($content)
    {
        return new self(self::guessContentType($content));
    }
    
    /**
     * @param string $content
     * @return string
     */
    public static function guessContentType($content)
    {
        $finfo = new \finfo();
        return $finfo->buffer($content, FILEINFO_MIME);
    }

    /**
     * Constructor : defines the current URL and gets the routes
     *
     * @param string $content_type
     */
    public function __construct($content_type)
    {
        $this->prepareContentType($content_type);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getContentType();
    }

// ----------------------
// Setters / Getters
// ----------------------

    /**
     * @param   string $content_type
     * @return  self
     * @throws  \Exception if the content_type was not declared and unknown
     */
    public function setContentType($content_type) 
    {
        if (in_array($content_type, self::$content_types)) {
            $this->content_type = $content_type;
        } elseif (array_key_exists($content_type, self::$content_types)) {
            $this->content_type = self::$content_types[$content_type];
        } else {
            throw new \Exception(
                sprintf('Unknown content type "%s"!', $content_type)
            );
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType() 
    {
        return $this->content_type;
    }

    /**
     * @param \Library\HttpFundamental\ContentTypeInterface $content_type_object
     * @return self
     */
    public function setContentTypeObject(ContentTypeInterface $content_type_object) 
    {
        $this->content_type_object = $content_type_object;
        $this->setContentType($this->content_type_object->getContentType());
        return $this;
    }

    /**
     * @return object|null Object implementing the `\Library\HttpFundamental\ContentTypeInterface`
     */
    public function getContentTypeObject() 
    {
        return $this->content_type_object;
    }

// ----------------------
// Process
// ----------------------

    /**
     * @param string $content_type
     * @return self
     */
    public function prepareContentType($content_type) 
    {
        $_cls = '\Library\HttpFundamental\ContentType\\'.TextHelper::toCamelCase($content_type);
        if (class_exists($_cls)) {
            return $this->setContentTypeObject(new $_cls);
        } else {
            return $this->setContentType($content_type);
        }
    }

    /**
     * Prepare the content of the response before to send it to client
     *
     * @param \Library\HttpFundamental\Response $response
     * @return void
     */
    public function prepareResponse(Response $response)
    {
        $cto = $this->getContentTypeObject();
        if (!empty($cto)) {
            $ctt_type = $cto->getContentType();
        } else {
            $ctt_type = $this->getContentType();
        }
        $response->setContentType($ctt_type);
    }

    /**
     * Parse an input content
     *
     * @param string $content
     * @return mixed
     */
    public function parseContent($content)
    {
        $cto = $this->getContentTypeObject();
        if (!empty($cto)) {
            return $cto->parseContent($content);
        } else {
            return $content;
        }
    }

    /**
     * Prepare a content for output
     *
     * @param mixed $content
     * @return string
     */
    public function prepareContent($content)
    {
        $cto = $this->getContentTypeObject();
        if (!empty($cto)) {
            return $cto->prepareContent($content);
        } else {
            return (string) $content;
        }
    }

}

// Endfile
