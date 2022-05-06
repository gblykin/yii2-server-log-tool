<?php

namespace app\services\helpers;

class UserAgentHelper
{
    public static function getUserAgentInfo($userAgent){
        $key = md5($userAgent);
        return \Yii::$app->cache->getOrSet($key, function () use ($userAgent) {
            return get_browser($userAgent, true);
        });
    }
}