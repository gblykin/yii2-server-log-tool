<?php

namespace app\services\progressbars;

interface ProgressBarInterface
{
    public static function startProgress($done, $total, $prefix = '', $width = null);
    public static function updateProgress($done, $total, $prefix = null);
    public static function endProgress($remove = false, $keepPrefix = true);
}