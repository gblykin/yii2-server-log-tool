<?php

namespace app\services\parsers;

use app\services\progressbars\ProgressBarInterface;
use app\services\readers\ReaderInterface;
use app\services\writers\WriterInterface;

/**
 * base abstract class for parsing server logs
 */
abstract class AbstractParser{
    protected string $logFile;

    protected ReaderInterface $reader;
    protected WriterInterface $writer;
    protected ProgressBarInterface $progressBar;

    /**
     * Constructor
     *
     * @param ReaderInterface $reader class for read file
     * @param WriterInterface $writer class for writing data
     * @param ProgressBarInterface $progressBar class for show progress of parsing
     */
    public function __construct(ReaderInterface $reader, WriterInterface $writer, ProgressBarInterface $progressBar)
    {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->progressBar = $progressBar;
    }

    /**
     * Main function for parsing
     *
     * @param array $params
     * @return mixed
     */
    abstract function parse(array $params);

    /**
     * Write message to log file
     *
     * @param string $message
     */
    function log(string $message){
        if (!empty($this->logFile)){
            file_put_contents($this->logFile, $message . PHP_EOL, FILE_APPEND);
        }
    }
}