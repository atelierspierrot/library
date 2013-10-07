<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */
namespace Library\HttpFundamental;

use Library\Converter\Html2Text;

/**
 * The global response class
 *
 * This is the global response of the application
 *
 * @author      Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Response
{

    const STATUS_OK = '200 OK';
    const STATUS_BAD_REQUEST = '400 Bad Request';
    const STATUS_NOT_FOUND = '404 Not Found';
    const STATUS_UNPROCESSABLE_ENTITY = '422 Unprocessable Entity';
    const STATUS_ERROR = '500 Internal Server Error';

    protected $protocol = 'HTTP/1.1';

    protected $status;

    protected $headers = array();

    /**
     * The response contents
     */
    protected $contents = array();

    /**
     * The response character set
     */
    protected $charset = 'utf-8';

    /**
     * @var object implementing the `\Library\HttpFundamental\ContentTypeInterface`
     */
    protected $content_type;

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
     * Constructor : defines the current URL and gets the routes
     */
    public function __construct($content = null, $charset = null)
    {
        if (!empty($content)) {
            if (is_array($content)) $this->setContent($content);
            else $this->addContent(null, $content);
        }
        if (!empty($charset)) $this->setCharset($charset);
    }

    public function __toString()
    {
        return $this->send(true);
    }

// ----------------------
// Setters / Getters
// ----------------------

    /**
     */
    public function setProtocol($value) 
    {
        $this->protocol = $value;
        return $this;
    }

    /**
     */
    public function getProtocol() 
    {
        return $this->protocol;
    }

    /**
     */
    public function setHeaders(array $headers) 
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     */
    public function addHeader($header, $value) 
    {
        $this->headers[$header] = $value;
        return $this;
    }

    /**
     */
    public function getHeader($header, $default = null) 
    {
        return array_key_exists($header, $this->headers) ? $this->headers[$header] : (
            array_key_exists(strtolower($header), $this->headers) ? $this->headers[strtolower($header)] : $default
        );
    }

    /**
     */
    public function getHeaders() 
    {
        return $this->headers;
    }

    /**
     */
    public function setStatus($flag) 
    {
        $this->status = $flag;
        return $this;
    }

    /**
     */
    public function getStatus() 
    {
        return $this->status;
    }

    /**
     */
    public function setCharset($string) 
    {
        $this->charset = $string;
        return $this;
    }

    /**
     */
    public function getCharset() 
    {
        return $this->charset;
    }

    /**
     */
    public function addContent($name, $content) 
    {
        if (is_null($name)) {
            array_push($this->contents, $content);
        } else {
            $this->contents[$name] = $content;
        }
        return $this;
    }

    /**
     */
    public function setContents(array $contents) 
    {
        $this->contents = array_merge($this->contents, $contents);
        return $this;
    }

    /**
     */
    public function getContent($name, $default = null) 
    {
        return array_key_exists($name, $this->contents) ? $this->contents[$name] : $default;
    }

    /**
     */
    public function getContents() 
    {
        return $this->contents;
    }

    /**
     */
    public function getContentsAsString() 
    {
        $content = '';
        foreach ($this->contents as $key=>$ctt) {
            $content .= $ctt;
        }
        return $content;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setContentType($type) 
    {
        $this->content_type = new ContentType($type);
        return $this;
    }

    /**
     * @return object
     */
    public function getContentType() 
    {
        return $this->content_type;
    }

    /**
     * @return self
     */
    public function guessContentType() 
    {
        $this->content_type = ContentType::createFromContent($this->getContentsAsString());
    }

// ----------------------
// Send
// ----------------------

	/**
	 * @return void
	 */
    public function renderHeaders()
    {
        self::header($this->getProtocol() . ' ' . $this->getStatus());
        foreach ($this->getHeaders() as $header=>$content) {
            self::header(ucfirst($header) . ': ' . $content);
        }
    }

    /**
     * Writes a header string if headers had not been sent
     *
     * @param string $str The header string
     */
    public static function header($str)
    {
        if (!headers_sent()) header($str);
    }

    /**
     * Send the response to the device
     */
    public function send($return_string = false) 
    {
        if (empty($this->content_type)) $this->guessContentType();

        $this->addHeader('Content-type', $this->content_type.'; charset='.strtoupper($this->getCharset()));
        $this->renderHeaders();

        $response = $this->content_type->prepareContent($this->getContents());
        if ($return_string) {
            return $response;
        } else {
            echo $response;
            exit("\n");
        }
    }

    /**
     * Force client to download a file
     */
    public function download($file = null, $type = null, $file_name = null) 
    {
        if (!empty($file) && @file_exists($file)) {
            if (is_null($file_name)) 
              $file_name = end( explode('/', $file) );
            $this->addHeader('Content-disposition', 'attachment; filename='.$file_name);
            $this->addHeader('Content-Type', 'application/force-download');
            $this->addHeader('Content-Transfer-Encoding', $type);
            $this->addHeader('Content-Length', filesize($file));
            $this->addHeader('Pragma', 'no-cache');
            $this->addHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0, public');
            $this->addHeader('Expires', '0'); 
            $this->renderHeaders();
            readfile( $file );
            exit;
        }
        return;
    }

    /**
     * Flush (display) a file content
     */
    public function flush($file_content = null, $type = null) 
    {
        if (!empty($file_content)) {
            if (empty($type)) {
                $finfo = new \finfo();
                $type = $finfo->buffer($file_content, FILEINFO_MIME);
            }
            $this->addHeader('Content-Type', $type);
            $this->renderHeaders();
            echo $file_content;
            exit;
        }
        return;
    }

    public function redirect($url, $permanent = false)
    {
        if ($permanent) {
            $this->addHeader('Status', '301 Moved Permanently');
        } else {
            $this->addHeader('Status', '302 Found');
        }
        $this->addHeader('location', $url);
    }
    
}

// Endfile
