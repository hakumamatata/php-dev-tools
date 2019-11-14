<?php
/**
 * 記憶體釋放 使用 (效能取較佳者)
 * v1.0 2019/11/14
 */

namespace app\models\Traits\Methods;

/**
 * Trait MemoryRelease
 * @package app\models\Traits\Methods
 */
trait MemoryRelease
{
    /**
     * @param mixed $var 需要釋放記憶體的變數
     */
    protected function releaseMemory(&$var)
    {
//        效率關係 PHP版本不同 使用不同方式處理
        if (PHP_VERSION_ID > 6) {
//            php ver. 7.X
            unset($var);
        } else {
//             php ver. 5.X
            $var = null;
        }
    }
}
