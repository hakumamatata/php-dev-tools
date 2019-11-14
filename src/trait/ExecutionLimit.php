<?php
/**
 * php.ini...等 執行時間受限控制
 * v1.0 2019/11/14
 */

namespace app\models\Traits\Methods;

/**
 * Trait ExecutionLimit
 * @package app\models\Traits\Methods
 */
trait ExecutionLimit
{
    /**
     * @param string $max_execution_time 最大執行時間
     * @param string $memory_limit 最大使用記憶體
     */
    protected function setExecutionLimit($max_execution_time = '3600', $memory_limit = '3072M')
    {
        $defaultMaxExecutionTime = ini_get('max_execution_time');
        $defaultMemoryLimit = ini_get('memory_limit');

        if (intval($max_execution_time) > intval($defaultMaxExecutionTime)) {
            ini_set('max_execution_time', $max_execution_time);
        }
        if (intval($memory_limit) > intval($defaultMemoryLimit)) {
            ini_set('memory_limit', $memory_limit);
        }
    }
}
