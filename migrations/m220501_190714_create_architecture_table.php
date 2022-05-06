<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%architecture}}`.
 */
class m220501_190714_create_architecture_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%architecture}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%architecture}}');
    }
}
