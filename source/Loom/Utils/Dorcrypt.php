<?php
  namespace Loom\Utils;
  
  /**
   * Dorcrypt - a utility to use cryptology in a simpler way with PHP
   */
  class Dorcrypt
  {
    public const SHA1 = "sha1";
    public const SHA256 = "sha256";
    public const SHA512 = "sha512";
    public const SHA3 = "sha3-512";
    public const MD5 = "md5";
    public const MD2 = "md2";
    public const WHIRLPOOL = "whirlpool";
    public const SNEFRU = "snefru";
    public const GOST = "gost";

    public static function compareHash($knownHash, $hash)
    {
      if (is_string($hash) && is_string($knownHash)) {
        return hash_equals($knownHash, $hash);
      } else return false; # if not a string        
    }

    public static function hash($algo, $content)
    {
      if (is_string($content)) {
        return hash($algo, $content);
      } else return false; # if not a string 
    }
    
    public static function sha256($content)
    {
      return self::hash(self::SHA256, $content);
    }

    public static function snefru($content)
    {
      return self::hash(self::SNEFRU, $content);
    }

    public static function gost($content)
    {
      return self::hash(self::GOST, $content);
    }

    public static function sha3($content)
    {
      return self::hash(self::SHA3, $content);
    }

    public static function sha1($content)
    {
      return self::hash(self::SHA1, $content);   
    }

    public static function sha512($content)
    {
      return self::hash(self::SHA512, $content);
    }

    public static function md5($content)
    {
      return self::hash(self::MD5, $content);
    }
    
    public static function whirlpool($content)
    {
      return self::hash(self::WHIRLPOOL, $content);
    }

    public static function hashFile($algo, $filePath)
    {
      if (is_file($filePath)) {
        return hash_file($algo, $filePath);
      } else return false; # if not a string
    }
  }