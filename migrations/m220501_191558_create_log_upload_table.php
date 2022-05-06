<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log_upload}}`.
 */
class m220501_191558_create_log_upload_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log_upload}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(1024)->notNull(),
            'started_at' => $this->integer(),
            'finished_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%log_upload}}');
    }
}
