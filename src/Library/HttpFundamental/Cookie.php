<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (ↄ) 2013-2015 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/library>
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
 */
namespace Library\HttpFundamental;

/**
 */
class Cookie
{
    
    /**
     * Transform array values as flatten string (`serialize` like)
     */
    const FLATNESS_ARRAY        = 1;

    /**
     * Transform array values as array cookies, named like `cookie_name[array_index]`
     */
    const INDEXED_COOKIES_ARRAY = 2;
    
    /**
     * @var array Ordered list of the `setcookie()` PHP internal function
     */
    protected static $SET_COOKIE_ARGUMENTS = array(
        0=>'name',
        1=>'value',
        2=>'expire',
        3=>'path',
        4=>'domain',
        5=>'secure',
        6=>'httponly'
    );
    
    /**
     * @var string Name of the cookie
     */
    protected $name;

    /**
     * @var mixed Value of the cookie
     */
    protected $value;

    /**
     * @var int Expiration time for the cookie (UNIX timestamp)
     */
    protected $expire;

    /**
     * @var string Server path where the cookie is accessible
     */
    protected $path;

    /**
     * @var string Domain name where the cookie is accessible (it will be readable for the domain and all its sub-domains)
     */
    protected $domain;

    /**
     * @var bool Wether to SEND the cookie ONLY for HTTPS protocol
     */
    protected $secure;

    /**
     * @var bool Wether to READ the cookie ONLY through HTTPS protocol
     */
    protected $httponly;

    /**
     * @var string Internal PHP function name to use (`setcookie` or `setrawcookie`)
     * @see self::sendAsRaw()
     * @see self::sendAsEncoded()
     */
    private $_cookie_func = 'setcookie';

    /**
     * @var array Table of arguments used when calling the `setcookie()` PHP internal function
     */
    private $_arguments = array();

    /**
     * @param string $cookie_name
     * @param string|array $cookie_value
     * @param int $flag
     */
    public function __construct($cookie_name = null, $cookie_value = null, $flag = self::FLATNESS_ARRAY)
    {
        $this->setFlag($flag);
        if (!empty($cookie_name)) {
            $this
                ->setName($cookie_name)
                ->read();
        }
        if (!empty($cookie_value)) {
            $this->setValue($cookie_value);
        }
    }

// ------------------
// Setters / Getters
// ------------------
    
    /**
     * @param int $flag
     * @return self
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $value
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * @return string
     */
    public function getSafeValue()
    {
        return '';
    }
    
    /**
     * @param int $expire
     * @return self
     */
    public function setExpire($expire)
    {
        $this->expire = (int) $expire;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getExpire()
    {
        return (int) (0===(int) $this->expire) ? 0 : time() + $this->expire;
    }
    
    /**
     * @return int
     */
    public function getSafeExpire()
    {
        return 0;
    }
    
    /**
     * @param string $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * @return string
     */
    public function getSafePath()
    {
        return '/';
    }
    
    /**
     * @param string $domain
     * @return self
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }
    
    /**
     * @return string
     */
    public function getSafeDomain()
    {
        return $_SERVER['SERVER_NAME'];
    }
    
    /**
     * @param string $secure
     * @return self
     */
    public function setSecure($secure)
    {
        $this->secure = (bool) $secure;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getSecure()
    {
        return (bool) $this->secure;
    }
    
    /**
     * @return bool
     */
    public function getSafeSecure()
    {
        return false;
    }
    
    /**
     * @param string $https
     * @return self
     */
    public function setHttponly($https)
    {
        $this->httponly = (bool) $https;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getHttponly()
    {
        return (bool) $this->httponly;
    }

    /**
     * @return bool
     */
    public function getSafeHttponly()
    {
        return false;
    }

    /**
     * @return self
     */
    public function sendAsRaw()
    {
        $this->_cookie_func = 'setrawcookie';
        return $this;
    }

    /**
     * @return self
     */
    public function sendAsEncoded()
    {
        $this->_cookie_func = 'setcookie';
        return $this;
    }

    /**
     * @param array $args
     * @return self
     */
    public function setCookieFuncArguments(array $args)
    {
        $this->_arguments = $args;
        return $this;
    }
    
    /**
     * @param bool $organize
     * @return array
     */
    public function getCookieFuncArguments($organize = true)
    {
        if (true===$organize) {
            $this->_organizeCookieFuncArguments();
        }
        return $this->_arguments;
    }
    
    /**
     * @param string $var
     * @return mixed
     */
    public function getCookieFuncArgument($var)
    {
        return isset($this->_arguments[$var]) ? $this->_arguments[$var] : null;
    }

    /**
     * @param string $var
     * @param mixed $val
     * @return self
     */
    public function addCookieFuncArgument($var, $val)
    {
        $this->_arguments[$var] = $val;
        return $this;
    }

    /**
     * @param string $var
     * @return self
     */
    public function clearCookieFuncArgument($var)
    {
        if (isset($this->_arguments[$var])) {
            unset($this->_arguments[$var]);
        }
        return $this;
    }

    /**
     * @return void
     */
    protected function _organizeCookieFuncArguments()
    {
        $original_args = $this->_arguments;
        $this->_arguments = array();
        $missing_var = $missing_index = null;
        foreach (self::$SET_COOKIE_ARGUMENTS as $arg_index=>$arg_var) {
            $arg_val = $this->_getSafeCookieFuncArgument($arg_var, false);
            if (isset($arg_val)) {
                if (!empty($missing_index)) {
                    $this->_arguments[$missing_index] = $this->_getSafeCookieFuncArgument($missing_var);
                    $missing_index = null;
                    $missing_var = null;
                }
                $this->_arguments[$arg_index] = $arg_val;
            } else {
                $missing_index = $arg_index;
                $missing_var = $arg_var;
            }
        }
    }

    /**
     * @param string $var_name
     * @param bool $safe_value
     * @return mixed
     * @throws \RuntimeException
     */
    protected function _getSafeCookieFuncArgument($var_name, $safe_value = true)
    {
        $_meth = 'get'.ucfirst($var_name);
        $_meth_safe = 'getSafe'.ucfirst($var_name);
        if (method_exists($this, $_meth) && is_callable(array($this, $_meth))) {
            return call_user_func(array($this, $_meth));
        } elseif (true===$safe_value && method_exists($this, $_meth_safe) && is_callable(array($this, $_meth_safe))) {
            return call_user_func(array($this, $_meth_safe));
        } else {
            throw new \RuntimeException(
                sprintf('Unknown method "%s::%s" nor "%s::%s" for required cookie function argument!', __CLASS__, $_meth, __CLASS__, $_meth_safe)
            );
        }
    }

// ------------------
// Cookies manipulation
// ------------------

    /**
     * Test if a cookie exists
     * 
     * @param string $cookie_name
     * @return bool
     */
    public function exists($cookie_name = null)
    {
        if (!empty($cookie_name)) {
            $this->setName($cookie_name);
        }
        return isset($_COOKIE[$this->getName()]);
    }
   
    /**
     * Get a cookie value
     * 
     * @param string $cookie_name
     * @return string|array
     */
    public function read($cookie_name = null)
    {
        if (!empty($cookie_name)) {
            $this->setName($cookie_name);
        }
        $val = isset($_COOKIE[$this->getName()]) ? $_COOKIE[$this->getName()] : null;
        if ($val && is_string($val) && ($this->getFlag() & self::FLATNESS_ARRAY)) {
            parse_str($val, $tmp_val); 
            if (!empty($tmp_val) && $tmp_val!=$val) {
                $val = $tmp_val;
            }
        }
        $this->setValue($val);
        return $this->getValue();
    }
   
    /**
     * Set a cookie value
     * 
     * @param string $cookie_name
     * @param string|array $cookie_value
     * @return bool
     */
    public function send($cookie_name = null, $cookie_value = null)
    {
        if (!empty($cookie_name)) {
            $this->setName($cookie_name);
        }
        if (!empty($cookie_value)) {
            $this->setValue($cookie_value);
        }
        $final_value = $this->getValue();
        if (is_array($final_value) && ($this->getFlag() & self::FLATNESS_ARRAY)) {
            return $this
                ->setValue(http_build_query($final_value))
                ->send();
        } elseif (is_array($final_value) && ($this->getFlag() & self::INDEXED_COOKIES_ARRAY)) {
            foreach ($final_value as $variable=>$value) {
                $ok = $this->addInCookie($variable, $value);
            }
            return $ok;
        } else {
            return call_user_func_array($this->_cookie_func, $this->getCookieFuncArguments());
        }
    }
   
    /**
     * Add a variable value in a cookie
     * 
     * @param string $variable_name
     * @param string $variable_value
     * @param string $cookie_name
     * @return bool
     */
    public function addInCookie($variable_name, $variable_value, $cookie_name = null)
    {
        if (!empty($cookie_name)) {
            $this->setName($cookie_name);
        }
        if (!empty($variable_value)) {
            $this->setValue($variable_value);
        }
        return $this
            ->setName($this->getName().'['.$variable_name.']')
            ->send();
    }
   
    /**
     * Clear a cookie
     * 
     * @param string $cookie_name
     * @return bool
     */
    public function clear($cookie_name = null)
    {
        if (!empty($cookie_name)) {
            $this->setName($cookie_name);
        }
        return $this
            ->setValue(null)
            ->setExpire(-1000)
            ->send()
            ;
    }
   
}

// Endfile
