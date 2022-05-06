<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%os}}`.
 */
class m220501_190304_create_os_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%os}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%os}}');
    }
}
