<?php

namespace app\models\Functions;

use yii\helpers\Json;

/**
 * Class Curl
 * @package app\models\Functions
 */
class Curl
{
    /**
     * @var array
     */
    protected $curlErrors = [];

    /**
     * @return array
     */
    public function getCurlErrors(): array
    {
        return $this->curlErrors;
    }

    /**
     * @param $curlErrors
     */
    public function setCurlErrors($curlErrors)
    {
        if (is_array($curlErrors) || is_string($curlErrors) || is_object($curlErrors)) {
            $this->curlErrors[] = $curlErrors;
        }
    }

    /**
     * 單純 POST 方式 CURL
     * @param $data
     * @param string $url
     * @param array $options
     * @return bool|mixed
     */
    public function curlByPost($data, string $url, array $options = [])
    {
        $curl = curl_init();
        $params = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false, //https 略過憑證檢查
            CURLOPT_SSL_VERIFYPEER => false, //https 略過憑證檢查
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
        ];
        curl_setopt_array($curl, $params);
        $response = curl_exec($curl);

        if (!curl_errno($curl)) {
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($http_code) {
                /**
                 * $http_code 成功、錯誤
                 * @link https://developer.mozilla.org/zh-TW/docs/Web/HTTP/Status 參照MDN
                 */
                if (200 <= $http_code && $http_code < 300) {
                    curl_close($curl);
                    return $response;
                } elseif (400 <= $http_code && $http_code < 600) {
                    $this->setCurlErrors('Error $http_code ' . $http_code);
                    curl_close($curl);
                    return Json::encode(['status' => 'error', 'msg' => '執行請求錯誤<br> (Http Code:' . $http_code . ')']);
                } else {
                    $this->setCurlErrors('Other $http_code ' . $http_code);
                    curl_close($curl);
                    return false;
                }
            } else {
                $this->setCurlErrors('Empty $http_code');
                curl_close($curl);
                return false;
            }
        } else {
            $this->setCurlErrors(curl_error($curl));
            curl_close($curl);
            return false;
        }
    }
}