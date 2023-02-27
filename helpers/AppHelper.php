<?php
namespace app\helpers;

/**
 * AppHelper for different functions needed in project
 */
class AppHelper
{

    /**
     * returns timestamp for start of month from $time param
     *
     * @param int|null $time timestamp
     * @return false|int
     */
    public static function startOfMonth(int $time = NULL){
        if ($time === NULL){
            $time = time();
        }
        $startDay= mktime(0, 0, 0, date("m", $time), 1, date("y", $time));
        return $startDay;
    }

    /**
     * returns finish of day (23:59:59) of day from $time param
     *
     * @param int $time timestamp
     * @return false|int
     */
    public static function finishOfDay(int $time){
        return mktime(23, 59, 59, date("m", $time), date("d", $time), date("y", $time));
    }

}