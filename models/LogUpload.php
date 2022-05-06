<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%log_upload}}".
 *
 * @property int $id
 * @property string $title
 * @property int|null $started_at
 * @property int|null $finished_at
 *
 * @property Log[] $logs
 */
class LogUpload extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log_upload}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['started_at'], 'required'],
            [['started_at', 'finished_at'], 'integer'],
            [['title'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'started_at' => Yii::t('app', 'Started At'),
            'finished_at' => Yii::t('app', 'Finished At'),
        ];
    }

    /**
     * Gets query for [[Logs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::className(), ['log_upload_id' => 'id']);
    }
}
