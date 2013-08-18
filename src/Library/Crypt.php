<?php
/**
 * PHP Library package of Les Ateliers Pierrot
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/library>
 */

namespace Library;

/**
 * Simple crypter 
 *
 * @author      Pierre Cassat & contributors <piero.wbmstr@gmail.com>
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
