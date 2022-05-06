<?php

namespace app\commands;

use app\services\parsers\LogFileParser;
use app\services\progressbars\ConsoleProgressBar;
use app\services\readers\LogFileReader;
use app\services\writers\DBWriter;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use Exception;
use yii\helpers\Console;

/**
 * Controller for parsing of file or directory from first argument that you have entered.
 */
class ParsingController extends Controller
{
    /**
     * This command make parsing of log-file or directory (with logs) from first argument that you have entered.
     * @param string $path the abs path to file or directory for parsing of log (logs).
     * @return int Exit code
     * @throws Exception
     */
    public function actionParseFiles(string $path): int
    {
        $startTime = time();
        $parser = new LogFileParser(
            new LogFileReader('%h %l %u %t "%m %U %P" %>s %O "%{Referer}i" \"%{User-Agent}i"'),
            new DBWriter(\yii\db\ActiveRecord::class),
            new ConsoleProgressBar(),
        );
        $parser->init($path);
        $parser->parse([]);
        $parser->setFinishTime();
        echo (Yii::t('app', 'Total processing time: {time} sec', ['time' => (time() - $startTime)]));
        return ExitCode::OK;
    }
}
