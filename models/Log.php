<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%log}}".
 *
 * @property int $id
 * @property int $log_upload_id
 * @property string|null $ip
 * @property string $date
 * @property int|null $url_id
 * @property string|null $user_agent_raw
 * @property int|null $os_id
 * @property int|null $architecture_id
 * @property int|null $browser_id
 *
 * @property Architecture $architecture
 * @property Browser $browser
 * @property LogUpload $logUpload
 * @property Os $os
 * @property Url $url
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_upload_id', 'date'], 'required'],
            [['log_upload_id', 'url_id', 'os_id', 'architecture_id', 'browser_id'], 'integer'],
            [['date'], 'safe'],
            [['ip', 'user_agent_raw'], 'string', 'max' => 255],
            [['architecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Architecture::className(), 'targetAttribute' => ['architecture_id' => 'id']],
            [['browser_id'], 'exist', 'skipOnError' => true, 'targetClass' => Browser::className(), 'targetAttribute' => ['browser_id' => 'id']],
            [['log_upload_id'], 'exist', 'skipOnError' => true, 'targetClass' => LogUpload::className(), 'targetAttribute' => ['log_upload_id' => 'id']],
            [['os_id'], 'exist', 'skipOnError' => true, 'targetClass' => Os::className(), 'targetAttribute' => ['os_id' => 'id']],
            [['url_id'], 'exist', 'skipOnError' => true, 'targetClass' => Url::className(), 'targetAttribute' => ['url_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'log_upload_id' => Yii::t('app', 'Log Upload ID'),
            'ip' => Yii::t('app', 'Ip'),
            'date' => Yii::t('app', 'Date'),
            'url_id' => Yii::t('app', 'Url ID'),
            'user_agent_raw' => Yii::t('app', 'User Agent Raw'),
            'os_id' => Yii::t('app', 'Os'),
            'architecture_id' => Yii::t('app', 'Architecture'),
            'browser_id' => Yii::t('app', 'Browser'),
        ];
    }

    /**
     * Gets query for [[Architecture]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArchitecture()
    {
        return $this->hasOne(Architecture::className(), ['id' => 'architecture_id']);
    }

    /**
     * Gets query for [[Browser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBrowser()
    {
        return $this->hasOne(Browser::className(), ['id' => 'browser_id']);
    }

    /**
     * Gets query for [[Browser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPopularBrowser()
    {
        return $this->hasOne(Browser::className(), ['id' => 'popular_browser_id']);
    }

    /**
     * Gets query for [[LogUpload]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogUpload()
    {
        return $this->hasOne(LogUpload::className(), ['id' => 'log_upload_id']);
    }

    /**
     * Gets query for [[Os]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOs()
    {
        return $this->hasOne(Os::className(), ['id' => 'os_id']);
    }

    /**
     * Gets query for [[Url]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUrl()
    {
        return $this->hasOne(Url::className(), ['id' => 'url_id']);
    }
}
