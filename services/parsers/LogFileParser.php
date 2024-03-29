<?php

namespace app\services\parsers;

use app\models\Architecture;
use app\models\Browser;
use app\models\LogUpload;
use app\models\Os;
use app\models\Url;
use app\services\progressbars\ProgressBarInterface;
use app\services\readers\ReaderInterface;
use app\services\writers\WriterInterface;
use SplFileObject;
use yii\base\InvalidConfigException;
use Yii;
use yii\db\Exception;
use app\services\helpers\UserAgentHelper;

/**
 * LogFileParser is the class that implements the AbstractParser for parsing server logs
 */
class LogFileParser extends AbstractParser
{
    public int $linesInIteration = 100;
    public LogUpload $logUpload;
    public string $path;


    /**
     * Constructor
     *
     * @param ReaderInterface $reader
     * @param WriterInterface $writer
     * @param ProgressBarInterface $progressBar
     * @throws Exception
     */
    public function __construct(ReaderInterface $reader, WriterInterface $writer, ProgressBarInterface $progressBar)
    {
        $logUpload = new LogUpload([
            'started_at' => time(),
        ]);
        if ($logUpload->save()){
            $this->logUpload = $logUpload;
        }else{
            throw new Exception(Yii::t('yii', 'LogUpload model cannot be saved'));
        }

        parent::__construct($reader, $writer, $progressBar);
    }

    /**
     * Init additional parameters of Parser entity
     *
     * @param $path
     */
    public function init($path){
        $this->path = $path;
        $this->setLogUploadName();
        $this->logFile = Yii::getAlias('@app') . '/tmp/parser-log_'.$this->logUpload->id . '.log';
    }

    /**
     * Set name of assigned LogUpload model
     */
    public function setLogUploadName(){
        $this->logUpload->title = $this->path;
        $this->logUpload->save();
    }

    /**
     * Set finish time for assigned LogUpload model
     */
    public function setFinishTime(){
        $this->logUpload->finished_at = time();
        $this->logUpload->save();
    }

    /**
     * Validate params
     *
     * @throws InvalidConfigException
     */
    protected function validate(array $params){
        if (empty($this->path)){
            throw new InvalidConfigException(Yii::t('yii', 'Missing required arguments: {params}', [
                'params' => 'path',
            ]));
        }

        if (!is_file($this->path) && !is_dir($this->path)){
            throw new InvalidConfigException(Yii::t('app', 'No such file or directory: {path}', [
                'path' => $this->path,
            ]));
        }
    }

    /**
     * returns files list from $path directory
     *
     * @param $path
     * @return array
     */
    public function getFilesFromDirectory($path): array
    {
        $files = scandir($path);
        $resultFiles = [];
        if (!empty($files)){
            foreach ($files as $file){
                if (!in_array($file, ['.', '..'])){
                    $resultFiles[] = $path . DIRECTORY_SEPARATOR . $file;
                }
            }
        }
        return $resultFiles;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function prepareData(array $data): array
    {
        $resultData = [];
        if (!empty($data)){
            $userAgentInfoList = [];
            $osList = [];
            $architectureList = [];
            $browserList = [];
            $urlList = [];
            foreach ($data as $dataItem){
                $userAgentInfo = UserAgentHelper::getUserAgentInfo($dataItem['HeaderUserAgent']);
                $userAgentInfoList[$dataItem['HeaderUserAgent']] = $userAgentInfo;
                $osList[$userAgentInfo['platform']] = $userAgentInfo['platform'];
                $architectureList[$userAgentInfo['browser_bits']] = $userAgentInfo['browser_bits'];
                $browserList[$userAgentInfo['browser']] = $userAgentInfo['browser'];
                $url = trim($dataItem['URL']);
                $urlList[$url] = $url;
            }
            $osList = Os::getAndGenerateAssociatedList('title', 'id', $osList);
            $architectureList = Architecture::getAndGenerateAssociatedList('title', 'id', $architectureList);
            $browserList = Browser::getAndGenerateAssociatedList('title', 'id', $browserList);
            $urlList = Url::getAndGenerateAssociatedList('url', 'id', $urlList);
            foreach ($data as $dataItem){
                $resultItem = [];
                $resultItem['id'] = '';
                $resultItem['log_upload_id'] = $dataItem['log_upload_id'];
                $resultItem['ip'] = $dataItem['ip'];
                $resultItem['date'] = date('Y-m-d H:i:s', $dataItem['timestamp']);
                $resultItem['day'] = date('Y-m-d', $dataItem['timestamp']);
                $resultItem['url_id'] = $urlList[$dataItem['URL']];
                $resultItem['user_agent_raw'] = $dataItem['HeaderUserAgent'];
                $userAgentInfo = $userAgentInfoList[$dataItem['HeaderUserAgent']];
                $resultItem['os_id'] = $osList[$userAgentInfo['platform']];
                $resultItem['architecture_id'] = $architectureList[$userAgentInfo['browser_bits']];
                $resultItem['browser_id'] = $browserList[$userAgentInfo['browser']];
                $resultData[] = $resultItem;
            }

            /*
             * Find info by queries for each item
            foreach ($data as $dataItem){
                $resultItem = [];
                $resultItem['id'] = '';
                $resultItem['log_upload_id'] = $dataItem['log_upload_id'];
                $resultItem['ip'] = $dataItem['ip'];
                $resultItem['timestamp'] = $dataItem['timestamp'];
                $resultItem['date'] = date('Y-m-d H:i:s', $dataItem['timestamp']);
                $resultItem['url_id'] = Url::getOrCreate('url', trim($dataItem['URL']))->id;
                $resultItem['user_agent_raw'] = $dataItem['HeaderUserAgent'];
                $userAgentInfo = UserAgentHelper::getUserAgentInfo($dataItem['HeaderUserAgent']);
                $resultItem['os_id'] = Os::getOrCreate('title', $userAgentInfo['platform'])->id;
                $resultItem['architecture_id'] = Architecture::getOrCreate('title', $userAgentInfo['browser_bits'])->id;
                $resultItem['browser_id'] = Browser::getOrCreate('title', $userAgentInfo['browser'])->id;
                $resultData[] = $resultItem;
            }
            */
        }
        return $resultData;
    }

    /**
     * returns count of lines in file by path $filePath
     *
     * @param string $filePath
     * @return int
     */
    public function linesCountInFile(string $filePath): int
    {
        $file = new SplFileObject($filePath, 'r');
        $file->seek(PHP_INT_MAX);
        $count = $file->key();
        $file = null;
        return $count + 1;
    }

    /**
     * Scan part of $filePath file.
     *
     * @param string $filePath file path
     * @param int $offset offset
     * @param int $count count of lines for reading
     * @param int $totalLinesCount total lines count in file
     */
    public function scanFile(string $filePath, int $offset, int $count, int $totalLinesCount)
    {
        $data = [];
        $file = new SplFileObject($filePath, 'r');
        $file->seek($offset);

        for ($i = 0; $i < $count; $i++){
            if (!$file->eof()){
                $line  =  $file->fgets();
                try {
                    $newDataItem = $this->reader->read($line);
                    $newDataItem['log_upload_id'] = $this->logUpload->id;
                    $data[] = $newDataItem;
                } catch (\Throwable $e) {
                    $this->log('Error: "' . $e->getMessage() . '". Line number: "' . ($offset + $i + 1) . '". Line content: ' . $line);
                }
            }else{
                break;
            }
        }

        $data = self::prepareData($data);

        $this->writer->write($data);

        $data = [];
        $this->progressBar::updateProgress($offset + $i,$totalLinesCount);
        if (!$file->eof()){
            $this->scanFile($filePath, $offset + $count, $count, $totalLinesCount);
        }
    }

    /**
     * Parsing of $filePath file
     *
     * @param string $filePath
     */
    public function readFile(string $filePath)
    {
        $totalLinesCount = $this->linesCountInFile($filePath);
        $this->progressBar::startProgress(0, $totalLinesCount);
        $this->scanFile($filePath, 0, 1000, $totalLinesCount);
        $this->progressBar::endProgress("end".PHP_EOL);
    }

    /**
     * Main process function for file parsing
     *
     * @throws InvalidConfigException
     */
    public function parse(array $params)
    {
        $this->validate($params);

        if (is_dir($this->path)){
            $files = $this->getFilesFromDirectory($this->path);
        }else{
            $files = [$this->path];
        }
        if (!empty($files)){
            foreach ($files as $filePath){
                $this->readFile($filePath);
            }
        }
    }
}