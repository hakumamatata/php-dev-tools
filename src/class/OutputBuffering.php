<?php
/**
 * output buffering 相關使用
 * 依賴 yii\helpers\Url
 * v1.0 2019/11/14
 */

namespace app\models\Functions;

use yii\helpers\Url;

/**
 * PHP OutputBuffering 使用的輔助物件
 * Class OutputBuffering
 * @package app\models\Functions
 */
class OutputBuffering
{
    /**
     * 瀏覽器會有至少要多少字元 才會刷新頁面使用
     * @var int
     */
    protected static $browserBufferLength = 4096;

    /**
     * 最近一次 out時間 timestamp
     * @var null
     */
    protected static $lastOutputTime = null;

    /**
     * 間隔 秒數
     */
    const OUTPUT_PERIOD_SEC = 1;

    /**
     * 是否要限制out 間隔時間 開關
     */
    const IS_LIMIT_OUTPUT_PEROID = true;

    /**
     * 防止短時間內 快速output訊息 避免前端容易造成解析錯誤
     * @return bool
     */
    protected static function checkOutputPeriod()
    {
        if (!self::IS_LIMIT_OUTPUT_PEROID) {
            return true;
        }

        if (self::$lastOutputTime) {
            $tempNewTime = time();
            if ($tempNewTime - self::$lastOutputTime <= self::OUTPUT_PERIOD_SEC) {
                return false;
            } else {
                self::$lastOutputTime = $tempNewTime;
                return true;
            }
        } else {
            self::$lastOutputTime = time();
            return true;
        }
    }

    /**
     * output buffering 初始化
     */
    public static function initOB()
    {
        header('Content-Encoding: none');

        ob_start();
        ob_implicit_flush();
    }

    /**
     * show start msg & loading img
     * @param string $title
     * @param string $warnMsg
     * @param string $loadImg
     */
    public static function startOB($title = '', $warnMsg = '', $loadImg = '/img/loading-1.gif')
    {
        self::initOB();
        echo '<div id="showProgressLoading" style="position: fixed; top: 30%; width: 30%; left: 35%; text-align: center; font-size: larger;">';
        echo '<p>' . $title . '</p>';
        echo '<p style="color: orangered; margin-bottom: 10px;">' . $warnMsg . '</p>';
        echo '<img src="' . Url::to('@web') . $loadImg . '" style="width: 50px; height: 50px;">';
        echo str_repeat(' ', self::$browserBufferLength);
        ob_flush();
    }

    /**
     * show progress with style
     * @param $msg
     */
    public static function showProgressMsg($msg)
    {
        if (self::checkOutputPeriod()) {
            echo '<p style="color: grey; margin: 3px;"><small>' . $msg . '</small></p>';
            echo str_repeat(' ', self::$browserBufferLength);
            ob_flush();
        }
    }

    /**
     * end with style , then clear all progress msg
     * @param string $msg
     */
    public static function endOB($msg = '')
    {
        echo '<p style="color: grey; margin: 3px;"><small>' . $msg . '</small></p>';
        sleep(3);
        echo '<pageBreak style="page-break-after: always;"></pageBreak>';
        echo str_repeat(' ', self::$browserBufferLength);
        echo '</div>';
        echo '<script>let sl = document.getElementById("showProgressLoading");sl.innerHTML="";</script>'; # 清空
        ob_end_flush();
    }

    /**
     * 純文字方法
     * @param $msg
     * @param bool $isEnd
     */
    public static function stringOnlyProgressMsg($msg, $isEnd = false)
    {
        if (self::checkOutputPeriod()) {
            echo $msg;
            echo str_repeat(' ', self::$browserBufferLength);
            ob_flush();
        }

        if ($isEnd) {
            ob_end_flush();
        }
    }

}