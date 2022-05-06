<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%browser}}".
 *
 * @property int $id
 * @property string $title
 *
 * @property Log[] $logs
 */
class Browser extends AppModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%browser}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * Gets query for [[Logs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::className(), ['browser_id' => 'id']);
    }
}
