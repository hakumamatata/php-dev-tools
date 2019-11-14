<?php
/**
 * QRcode 使用
 * 依賴 Da\QrCode\QrCode
 * v1.0 2019/11/14
 */

namespace app\models\Traits\Methods;

use Da\QrCode\QrCode;

/**
 * Trait QrGenerate
 * @package app\models\Traits\Methods
 */
trait QrGenerate
{
    /**
     * @var \Da\QrCode\QrCode 插件對象
     */
    protected $qrCodeObject;
    protected $qrDataUriConfig = [
        'size' => 250,
        'margin' => 5,
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];

    protected function qrCodeInit()
    {
        if (!$this->qrCodeObject) {
            $this->qrCodeObject = new QrCode();
        }
    }

    protected function getQrDataUriConfig($config = [])
    {
        $customConfig = [];

        if ($config) {
            foreach ($this->qrDataUriConfig as $key => $value) {
                if (isset($config[$key])) {
                    $customConfig[$key] = $config[$key];
                } else {
                    $customConfig[$key] = $value;
                }
            }
        }

        return $customConfig;
    }

    /**
     * 產生QrCode的DataUri
     *
     * @param string $msg QrCode訊息文字
     * @param array $config 設定參數
     * @return string QrCode的DataUri
     */
    protected function generateQRDataUri($msg, $config = [])
    {
        $this->qrCodeInit();

        $config = $this->getQrDataUriConfig($config);

        return $this->qrCodeObject
            ->setText($msg)
            ->setSize($config['size'])
            ->setMargin($config['margin'])
            ->useForegroundColor($config['red'], $config['green'], $config['blue'])
            ->writeDataUri();
    }
}
