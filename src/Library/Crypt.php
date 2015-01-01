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

namespace Library;

/**
 * Simple crypter 
 *
 * @author      Pierre Cassat & contributors <me@e-piwi.fr>
 */
class Crypt
{

    protected $salt = 'SIdCWq_yGhwxJwt#$/ 9RU*3&hkFw(mXj:AO4%hay|alf+bzic#p/DBY9v5G#Sn)';

    public function __construct($salt = null)
    {
        if (!is_null($salt)) $this->setSalt($salt);
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Generate Key for encryption / decryption : passage function
     *
     * @see self::encrypt()
     * @see self::unencrypt()
     */
    private function getEncryptKey($str = null)
    {
        $key = $this->getSalt();
        $key_crypt = md5($key);
        $count=0;
        $tmp = "";
        for ($ctr=0;$ctr<strlen($str);$ctr++) {
            if ($count==strlen($key_crypt))
                $count=0;
            $tmp.= substr($str,$ctr,1) ^ substr($key_crypt,$count,1);
            $count++;
        }
        return $tmp;
    }

    /**
     * Encryption function
     * 
     *     $Key="what"
     *     $MonTexte="Lorem ipsum."
     *     $str_encrypt = encrypt($MonTexte,$Key);
     * 
     * @param string $Str The string to crypt
     * @param string $Key The cryptage key
     * @return string
     */
    public function encrypt($str = null, $key = null)
    {
        if (!is_null($key)) $this->setSalt($key);
        srand((double)microtime()*1000000);
        $key_encrypt = md5(rand(0,32000));
        $count=0;
        $tmp = "";
        for ($ctr=0;$ctr<strlen($str);$ctr++) {
            if ($count==strlen($key_encrypt))
                $count=0;
            $tmp.= substr($key_encrypt,$count,1).(substr($str,$ctr,1) ^ substr($key_encrypt,$count,1) );
            $count++;
        }
        return base64_encode(self::getEncryptKey($tmp));
    }

    /**
     * Alias of self::encrypt
     */
    public function crypt($str = null, $key = null)
    {
        return $this->encrypt($str, $key);
    }

    /**
     * Decryption function
     *
     *     $Key="what"
     *     $Montexte = unencrypt($str_encrypt,$Key);
     *     $MonTexte="Lorem ipsum."
     * 
     * @param string $Str The string to unencrypt
     * @param string $Key Encryption key, which must be the same as for encryption
     * @return string
     */
    public function unencrypt($str=null, $key=null) 
    {
        if (!is_null($key)) $this->setSalt($key);
        $str = self::getEncryptKey(base64_decode($str));
        $tmp = "";
        for ($ctr=0;$ctr<strlen($str);$ctr++) {
            $md5 = substr($str,$ctr,1);
            $ctr++;
            $tmp.= (substr($str,$ctr,1) ^ $md5);
        }
        return $tmp;
    }
    
    /**
     * Alias of self::unencrypt
     */
    public function uncrypt($str = null, $key = null)
    {
        return $this->unencrypt($str, $key);
    }

}

// Endfile
