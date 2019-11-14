<?php
/**
 * 日期格式檢查
 * v1.0 2019/11/14
 */

namespace app\models\Traits\Methods;

trait DateHelper
{
    /**
     * 檢驗日期 時間 是否正確
     * @param $date
     * @param string $format
     * @return bool
     */
    protected function validateDateByFormat($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}