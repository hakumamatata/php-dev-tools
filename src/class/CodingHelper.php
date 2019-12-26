<?php
/**
 * 編碼、解碼 相關， 例如: 全形半形、字串長度...等
 */

namespace app\models\Functions;


class CodingHelper
{
    /**
     * 取得字串字數長度 (依照不同的編碼)
     * @param $string
     * @return int
     */
    public static function getStringLength($string, $encode = 'utf-8')
    {
        $length = 0;
        try {
            $length = mb_strlen($string, $encode);
        } catch (\Exception $e) {
            $newString = iconv(mb_detect_encoding($string), $encode, $string);
            $length = mb_strlen($newString, $encode);
        }
        return $length;
    }

    /**
     * 將一個字串中含有全形的數字字元、字母字元轉換為相應半形字元
     *
     * @param string $str 待轉換字串
     * @return string $str 處理後字串
     */
    public static function fullToHalfConvert($string)
    {
        $convertArr = array(
            '０' => '0',
            '１' => '1',
            '２' => '2',
            '３' => '3',
            '４' => '4',
            '５' => '5',
            '６' => '6',
            '７' => '7',
            '８' => '8',
            '９' => '9',
            'Ａ' => 'A',
            'Ｂ' => 'B',
            'Ｃ' => 'C',
            'Ｄ' => 'D',
            'Ｅ' => 'E',
            'Ｆ' => 'F',
            'Ｇ' => 'G',
            'Ｈ' => 'H',
            'Ｉ' => 'I',
            'Ｊ' => 'J',
            'Ｋ' => 'K',
            'Ｌ' => 'L',
            'Ｍ' => 'M',
            'Ｎ' => 'N',
            'Ｏ' => 'O',
            'Ｐ' => 'P',
            'Ｑ' => 'Q',
            'Ｒ' => 'R',
            'Ｓ' => 'S',
            'Ｔ' => 'T',
            'Ｕ' => 'U',
            'Ｖ' => 'V',
            'Ｗ' => 'W',
            'Ｘ' => 'X',
            'Ｙ' => 'Y',
            'Ｚ' => 'Z',
            'ａ' => 'a',
            'ｂ' => 'b',
            'ｃ' => 'c',
            'ｄ' => 'd',
            'ｅ' => 'e',
            'ｆ' => 'f',
            'ｇ' => 'g',
            'ｈ' => 'h',
            'ｉ' => 'i',
            'ｊ' => 'j',
            'ｋ' => 'k',
            'ｌ' => 'l',
            'ｍ' => 'm',
            'ｎ' => 'n',
            'ｏ' => 'o',
            'ｐ' => 'p',
            'ｑ' => 'q',
            'ｒ' => 'r',
            'ｓ' => 's',
            'ｔ' => 't',
            'ｕ' => 'u',
            'ｖ' => 'v',
            'ｗ' => 'w',
            'ｘ' => 'x',
            'ｙ' => 'y',
            'ｚ' => 'z'
        );
        return strtr($string, $convertArr);
    }


    /**
     * 取得字串中的第一位字 (可依據編碼)
     * @param $string
     * @return string
     */
    public static function getFirstWord($string, $encode = 'utf-8')
    {
        return mb_substr($string, 0, 1, $encode);
    }

    /**
     * 移除(替換)字串中的第一位字 (可依據編碼)
     * @param $string
     * @param string $replace
     * @param string $encode
     * @return mixed
     */
    public static function removeFirstWord($string, $replace = '', $encode = 'utf-8')
    {
        $firstWord = self::getFirstWord($string, $encode);
        return substr_replace($string, $replace, 0, strlen($firstWord));
    }

    /**
     * 去除字串中的空白
     * @param $string
     * @return string
     */
    public static function removeBlankWord($string)
    {
        $convertArr = array(
            ' ' => '',
            '　' => ''
        );
        return strtr($string, $convertArr);
    }


    /**
     * 取得字串中的某部分字串 (可依據編碼)
     * @param $string
     * @return string
     */
    public static function getWordsByRange($string, $start, $end, $encode = 'utf-8')
    {
        return mb_substr($string, $start, $end, $encode);
    }

    /**
     * 轉換 EXCEL 名稱管理員 特殊字元 為 自定義字元
     * @param $string
     * @return string
     */
    public static function replaceExcelNameRangeWord($string, $replace)
    {
        $convertArr = array(
            '(' => $replace,
            '（' => $replace,
            ')' => $replace,
            '）' => $replace,
            '/' => $replace,
            '／' => $replace,
            '\\' => $replace,
            '\＼' => $replace,
            ':' => $replace,
            '：' => $replace,
            '-' => $replace,
            '－' => $replace,
            '*' => $replace,
            '＊' => $replace,
            '+' => $replace,
            '＋' => $replace,
            '&' => $replace,
            '＆' => $replace,
            '^' => $replace,
            '︿' => $replace,
            '%' => $replace,
            '％' => $replace,
            '$' => $replace,
            '＄' => $replace,
            '#' => $replace,
            '＃' => $replace,
            '@' => $replace,
            '＠' => $replace,
            '!' => $replace,
            '！' => $replace,
            '~' => $replace,
            '～' => $replace,
            '{' => $replace,
            '｛' => $replace,
            '}' => $replace,
            '｝' => $replace,
            '[' => $replace,
            '［' => $replace,
            '【' => $replace,
            '「' => $replace,
            ']' => $replace,
            '］' => $replace,
            '】' => $replace,
            '」' => $replace,
            '|' => $replace,
            '｜' => $replace,
            '"' => $replace,
            '＂' => $replace,
            '\'' => $replace,
            '\’' => $replace,
            '>' => $replace,
            '＞' => $replace,
            '<' => $replace,
            '＜' => $replace,
            ',' => $replace,
            '，' => $replace,
            '=' => $replace,
            '＝' => $replace,
            '`' => $replace,
            '‵' => $replace,
            '﹑' => $replace,
            ';' => $replace,
            '；' => $replace,
        );
        return strtr($string, $convertArr);
    }

}