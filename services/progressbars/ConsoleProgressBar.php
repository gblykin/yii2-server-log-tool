<?php

namespace app\services\progressbars;

use yii\helpers\Console;

class ConsoleProgressBar implements ProgressBarInterface
{
    /**
     * Starts display of a progress bar on screen.
     *
     * @param int $done the number of items that are completed.
     * @param int $total the total value of items that are to be done.
     * @param string $prefix an optional string to display before the progress bar.
     * Default to empty string which results in no prefix to be displayed.
     * @param int|bool $width optional width of the progressbar. This can be an integer representing
     */
    public static function startProgress($done, $total, $prefix = '', $width = null){
        Console::startProgress($done, $total, $prefix = '', $width = null);
    }

    /**
     * Updates a progress bar that has been started by [[startProgress()]].
     *
     * @param int $done the number of items that are completed.
     * @param int $total the total value of items that are to be done.
     * @param string $prefix an optional string to display before the progress bar.
     */
    public static function updateProgress($done, $total, $prefix = null){
        Console::updateProgress($done, $total, $prefix = null);
    }

    /**
     * Ends a progress bar that has been started by [[startProgress()]].
     *
     * @param string|bool $remove This can be `false` to leave the progress bar on screen and just print a newline.
     * If set to `true`, the line of the progress bar will be cleared. This may also be a string to be displayed instead
     * of the progress bar.
     * @param bool $keepPrefix whether to keep the prefix that has been specified for the progressbar when progressbar
     * gets removed. Defaults to true.
     */
    public static function endProgress($remove = false, $keepPrefix = true){
        Console::endProgress($remove = false, $keepPrefix = true);
    }
}