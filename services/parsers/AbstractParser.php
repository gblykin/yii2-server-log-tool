<?php

namespace app\services\parsers;

use app\services\progressbars\ProgressBarInterface;
use app\services\readers\ReaderInterface;
use app\services\writers\WriterInterface;

abstract class AbstractParser{
    protected string $logFile;

    protected ReaderInterface $reader;
    protected WriterInterface $writer;
    protected ProgressBarInterface $progressBar;

    public function __construct(ReaderInterface $reader, WriterInterface $writer, ProgressBarInterface $progressBar)
    {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->progressBar = $progressBar;
    }

    abstract function parse(array $params);

    function log($message){
        if (!empty($this->logFile)){
            file_put_contents($this->logFile, $message . PHP_EOL, FILE_APPEND);
        }
    }
}