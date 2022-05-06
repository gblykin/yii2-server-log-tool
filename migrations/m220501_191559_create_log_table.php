<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%log_upload}}`
 * - `{{%url}}`
 * - `{{%os}}`
 * - `{{%architecture}}`
 * - `{{%browser}}`
 */
class m220501_191559_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'log_upload_id' => $this->integer()->notNull(),
            'ip' => $this->string(),
            "day" => $this->date(),
            'date' => $this->timestamp()->notNull(),
            'url_id' => $this->integer(),
            'user_agent_raw' => $this->string(),
            'os_id' => $this->integer(),
            'architecture_id' => $this->integer(),
            'browser_id' => $this->integer(),
        ]);

        // creates index for column `day`
        $this->createIndex(
            '{{%idx-log-day}}',
            '{{%log}}',
            'day'
        );

        // creates index for column `log_upload_id`
        $this->createIndex(
            '{{%idx-log-log_upload_id}}',
            '{{%log}}',
            'log_upload_id'
        );

        // add foreign key for table `{{%log_upload}}`
        $this->addForeignKey(
            '{{%fk-log-log_upload_id}}',
            '{{%log}}',
            'log_upload_id',
            '{{%log_upload}}',
            'id',
            'CASCADE'
        );

        // creates index for column `url_id`
        $this->createIndex(
            '{{%idx-log-url_id}}',
            '{{%log}}',
            'url_id'
        );

        // add foreign key for table `{{%url}}`
        $this->addForeignKey(
            '{{%fk-log-url_id}}',
            '{{%log}}',
            'url_id',
            '{{%url}}',
            'id',
            'CASCADE'
        );

        // creates index for column `os_id`
        $this->createIndex(
            '{{%idx-log-os_id}}',
            '{{%log}}',
            'os_id'
        );

        // add foreign key for table `{{%os}}`
        $this->addForeignKey(
            '{{%fk-log-os_id}}',
            '{{%log}}',
            'os_id',
            '{{%os}}',
            'id',
            'CASCADE'
        );

        // creates index for column `architecture_id`
        $this->createIndex(
            '{{%idx-log-architecture_id}}',
            '{{%log}}',
            'architecture_id'
        );

        // add foreign key for table `{{%architecture}}`
        $this->addForeignKey(
            '{{%fk-log-architecture_id}}',
            '{{%log}}',
            'architecture_id',
            '{{%architecture}}',
            'id',
            'CASCADE'
        );

        // creates index for column `browser_id`
        $this->createIndex(
            '{{%idx-log-browser_id}}',
            '{{%log}}',
            'browser_id'
        );

        // add foreign key for table `{{%browser}}`
        $this->addForeignKey(
            '{{%fk-log-browser_id}}',
            '{{%log}}',
            'browser_id',
            '{{%browser}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `day`
        $this->dropIndex(
            '{{%idx-log-day}}',
            '{{%log}}'
        );


        // drops foreign key for table `{{%log_upload}}`
        $this->dropForeignKey(
            '{{%fk-log-log_upload_id}}',
            '{{%log}}'
        );

        // drops index for column `log_upload_id`
        $this->dropIndex(
            '{{%idx-log-log_upload_id}}',
            '{{%log}}'
        );

        // drops foreign key for table `{{%url}}`
        $this->dropForeignKey(
            '{{%fk-log-url_id}}',
            '{{%log}}'
        );

        // drops index for column `url_id`
        $this->dropIndex(
            '{{%idx-log-url_id}}',
            '{{%log}}'
        );

        // drops foreign key for table `{{%os}}`
        $this->dropForeignKey(
            '{{%fk-log-os_id}}',
            '{{%log}}'
        );

        // drops index for column `os_id`
        $this->dropIndex(
            '{{%idx-log-os_id}}',
            '{{%log}}'
        );

        // drops foreign key for table `{{%architecture}}`
        $this->dropForeignKey(
            '{{%fk-log-architecture_id}}',
            '{{%log}}'
        );

        // drops index for column `architecture_id`
        $this->dropIndex(
            '{{%idx-log-architecture_id}}',
            '{{%log}}'
        );

        // drops foreign key for table `{{%browser}}`
        $this->dropForeignKey(
            '{{%fk-log-browser_id}}',
            '{{%log}}'
        );

        // drops index for column `browser_id`
        $this->dropIndex(
            '{{%idx-log-browser_id}}',
            '{{%log}}'
        );

        $this->dropTable('{{%log}}');
    }
}
