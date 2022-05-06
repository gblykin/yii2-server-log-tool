<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%url}}`.
 */
class m220501_185904_create_url_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%url}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(2100)->notNull()->defaultValue(''),
            'hash' => $this->char(32)->notNull(),
        ]);

        // creates index for column `hash`
        $this->createIndex(
            '{{%idx-url-hash}}',
            '{{%url}}',
            'hash'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `hash`
        $this->dropIndex(
            '{{%idx-url-hash}}',
            '{{%url}}'
        );

        $this->dropTable('{{%url}}');
    }
}
