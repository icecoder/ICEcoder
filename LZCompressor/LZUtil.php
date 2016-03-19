<?php
/**
 * Created by PhpStorm.
 * User: sics
 * Date: 27.02.2016
 * Time: 15:54
 */

namespace LZCompressor;


class LZUtil
{
    /**
    * @var string
    */
    public static $keyStrBase64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    public static $keyStrUriSafe = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+-$";
    private static $baseReverseDic = [];

    /**
     * @param string $alphabet
     * @param integer $character
     * @return string
     */
    public static function getBaseValue($alphabet, $character)
    {
        if(!array_key_exists($alphabet, self::$baseReverseDic)) {
            self::$baseReverseDic[$alphabet] = [];
            for($i=0; $i<strlen($alphabet); $i++) {
                self::$baseReverseDic[$alphabet][$alphabet{$i}] = $i;
            }
        }
        return self::$baseReverseDic[$alphabet][$character];
    }

    /**
     * @return string
     */
    public static function fromCharCode()
    {
        return array_reduce(func_get_args(), function ($a, $b) {
            $a .= self::utf8_chr($b);
            return $a;
        });
    }

    /**
     * Phps chr() equivalent for UTF-8 encoding
     *
     * @param int|string $u
     * @return string
     */
    public static function utf8_chr($u)
    {
        return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
    }

    /**
     * @param string $str
     * @param int $num
     *
     * @return bool|integer
     */
    public static function charCodeAt($str, $num=0)
    {
        return self::utf8_ord(self::utf8_charAt($str, $num));
    }

    /**
     * @param string $ch
     *
     * @return bool|integer
     */
    public static function utf8_ord($ch)
    {
        // must remain php's strlen
        $len = strlen($ch);
        if ($len <= 0) {
            return -1;
        }
        $h = ord($ch{0});
        if ($h <= 0x7F) return $h;
        if ($h < 0xC2) return -3;
        if ($h <= 0xDF && $len > 1) return ($h & 0x1F) << 6 | (ord($ch{1}) & 0x3F);
        if ($h <= 0xEF && $len > 2) return ($h & 0x0F) << 12 | (ord($ch{1}) & 0x3F) << 6 | (ord($ch{2}) & 0x3F);
        if ($h <= 0xF4 && $len > 3)
            return ($h & 0x0F) << 18 | (ord($ch{1}) & 0x3F) << 12 | (ord($ch{2}) & 0x3F) << 6 | (ord($ch{3}) & 0x3F);
        return -2;
    }

    /**
     * @param string $str
     * @param integer $num
     *
     * @return string
     */
    public static function utf8_charAt($str, $num)
    {
        return mb_substr($str, $num, 1, 'UTF-8');
    }

    /**
     * @param string $str
     * @return integer
     */
    public static function utf8_strlen($str) {
        return mb_strlen($str, 'UTF-8');
    }


}