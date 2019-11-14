<?php
/**
 * 通用解析物件工具
 * 目前依賴 yii\helpers\ArrayHelper
 * v1.0 2019/11/14
 */
namespace app\models\Functions;

use yii\helpers\ArrayHelper;

class ParserHelper
{
    /**
     * 取得Url query部分
     * @param string $url
     * @return array
     */
    public static function getUrlQuerys(string $url) : array
    {
        $errorResult = [];

        # 解析Url
        $urlComponents = parse_url($url);
        if (!$urlComponents || !ArrayHelper::getValue($urlComponents, 'query', null)) {
            return $errorResult;
        }
        parse_str($urlComponents['query'], $queryArray);
        if (!$queryArray) {
            return $errorResult;
        }

        return $queryArray;
    }

    /**
     * 取得Url中的查詢參數
     * @param string $url
     * @param $keys
     * @return string | array
     */
    public static function getUrlQueryValue(string $url, $keys)
    {
        $queryArray = self::getUrlQuerys($url);
        if ($queryArray) {
            $keysString = self::getYii2KeysString($keys);
            $value = ArrayHelper::getValue($queryArray, $keysString, null);
            if ($value) {
                return $value;
            }
        }

        return '';
    }

    /**
     * 設置Url中的查詢參數
     * 參數為陣列也可以執行、原始參數Key不存在時不會執行
     * @param string $url
     * @param $keys
     * @param string $setValue
     * @return string
     */
    public static function setUrlQueryValue(string $url, $keys, string $setValue) : string
    {
        $newUrl = '';
        $errorResult = '';

        # 解析Url
        $queryArray = self::getUrlQuerys($url);
        if (!$queryArray) {
            return $errorResult;
        }

        # keys防呆
        $keysString = self::getYii2KeysString($keys);
        if (!ArrayHelper::getValue($queryArray, $keysString, null)) {
            return $errorResult;
        }

        # 更新Query
        ArrayHelper::setValue($queryArray, $keysString, $setValue);
        $newQuery = http_build_query($queryArray);

        # 產生新的Url
        $urlComponents = parse_url($url);
        $newUrl = str_replace($urlComponents['query'], $newQuery, $url);

        return $newUrl;
    }

    /**
     * 取得 Yii2 ArrayHelper 可使用的KeysString
     * @param $keys
     * @return string
     */
    protected static function getYii2KeysString($keys): string
    {
        $keysString = '';
        if (is_array($keys)) {
            $keysString = implode('.', $keys);
        } else {
            if (is_string($keys)) {
                $keysString = $keys;
            }
        }
        return $keysString;
    }
}