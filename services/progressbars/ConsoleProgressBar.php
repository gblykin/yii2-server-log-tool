<?php

namespace app\services\progressbars;

use yii\helpers\Console;

class ConsoleProgressBar implements ProgressBarInterface
{
    public static function startProgress($done, $total, $prefix = '', $width = null){
        Console::startProgress($done, $total, $prefix = '', $width = null);
    }

    public static function updateProgress($done, $total, $prefix = null){
        Console::updateProgress($done, $total, $prefix = null);
    }

    public static function endProgress($remove = false, $keepPrefix = true){
        Console::endProgress($remove = false, $keepPrefix = true);
    }
}