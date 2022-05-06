<?php
namespace app\helpers;

class AppHelper
{
    public static function startOfMonth($time = NULL){
        if ($time === NULL){
            $time = time();
        }
        $startDay= mktime(0, 0, 0, date("m", $time), 1, date("y", $time));
        return $startDay;
    }

    public static function finishOfDay($time){
        $startDay= mktime(23, 59, 59, date("m", $time), date("d", $time), date("y", $time));
        return $startDay;
    }

}