<?php

namespace app\services\writers;

use app\models\Log;

class DBWriter implements WriterInterface
{
    public string $modelClass;

    public function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function write($data)
    {
        $dbTransaction = $this->modelClass::getDb()->beginTransaction();
        try {
            $this->modelClass::getDb()->createCommand()->batchInsert(
                Log::tableName(),
                ['id', 'log_upload_id', 'ip', 'date', 'day', 'url_id', 'user_agent_raw', 'os_id', 'architecture_id', 'browser_id'],
                $data
            )->execute();
            $dbTransaction->commit();
        } catch (\Throwable $e) {
            $dbTransaction->rollBack();
            throw $e;
        }
    }
}