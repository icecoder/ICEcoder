<?php
/**
 * Created by PhpStorm.
 * User: sics
 * Date: 27.02.2016
 * Time: 15:54
 */

namespace LZCompressor;


class LZUtil16
{

    /**
     * @return string
     */
    public static function fromCharCode()
    {
        return array_reduce(func_get_args(), function ($a, $b) {
            $a .= self::utf16_chr($b);
            return $a;
        });
    }

    /**
     * Phps chr() equivalent for UTF-16 encoding
     *
     * @param int|string $u
     * @return string
     */
    public static function utf16_chr($u)
    {
        return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-16', 'HTML-ENTITIES');
    }

    /**
     * @param string $str
     * @param int $num
     *
     * @return bool|integer
     */
    public static function charCodeAt($str, $num=0)
    {
        return self::utf16_ord(self::utf16_charAt($str, $num));
    }

    /**
     * @source http://blog.sarabande.jp/post/35970262740
     * @param string $ch
     * @return bool|integer
     */
    function utf16_ord($ch) {
        $length = strlen($ch);
        if (2 === $length) {
            return hexdec(bin2hex($ch));
        } else if (4 === $length) {
            $w1 = $ch[0].$ch[1];
            $w2 = $ch[2].$ch[3];
            if ($w1 < "\xD8\x00" || "\xDF\xFF" < $w1 || $w2 < "\xDC\x00" || "\xDF\xFF" < $w2) {
                return false;
            }
            $w1 = (hexdec(bin2hex($w1)) & 0x3ff) << 10;
            $w2 =  hexdec(bin2hex($w2)) & 0x3ff;
            return $w1 + $w2 + 0x10000;
        }
        return false;
    }

    /**
     * @param string $str
     * @param integer $num
     *
     * @return string
     */
    public static function utf16_charAt($str, $num)
    {
        return mb_substr($str, $num, 1, 'UTF-16');
    }

    /**
     * @param string $str
     * @return integer
     */
    public static function utf16_strlen($str)
    {
        return mb_strlen($str, 'UTF-16');
    }

}