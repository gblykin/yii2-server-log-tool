<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%url}}".
 *
 * @property int $id
 * @property string $url
 * @property string $hash
 *
 * @property Log[] $logs
 */
class Url extends AppModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%url}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hash'], 'required'],
            [['url'], 'string', 'max' => 2100],
            [['hash'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('app', 'Url'),
            'hash' => Yii::t('app', 'Hash'),
        ];
    }

    /**
     * Gets query for [[Logs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::className(), ['url_id' => 'id']);
    }

    public static function generateHash($value){
        return md5($value);
    }

    public static function getOrCreate($field, $value){
        $hash = self::generateHash($value);
        $model = self::find()
            ->andWhere(['hash' => $hash])
            ->andWhere(['url' => $value])
            ->limit(1)
            ->one();

        if (empty($model)){
            $model = new self(['url' => $value, 'hash' => $hash]);
            if (!$model->save()){
                throw new Exception(Yii::t('app', 'Model "'. self::class . '" cannot be created'));
            }
        }

        return $model;
    }

    public static function hashesList($list){
        return array_map('self::generateHash', $list);
    }

    public static function existsValues($field, $list){
        return self::find()
            ->select([$field])
            ->andWhere(['IN', 'hash', self::hashesList($list)])
            ->andWhere(['IN', $field, $list])
            ->asArray()
            ->column();
    }

    public static function createList($keyField, $valueField, $list){
        if (!empty($list)){
            $dataList = [];
            foreach ($list as $value){
                $dataList[] = [
                    $keyField => '',
                    $valueField => $value,
                    'hash' => self::generateHash($value),
                ];
            }

            static::getDb()->createCommand()->batchInsert(
                static::tableName(),
                [$keyField, $valueField, 'hash'],
                $dataList
            )->execute();
        }
    }

    public static function getAssociativeList($keyField, $valueField, $list){
        $associativeList = self::find()
            ->select([$keyField, $valueField])
            ->andWhere(['IN', 'hash', self::hashesList($list)])
            ->andWhere(['IN', $keyField, $list])
            ->asArray()
            ->all();

        return ArrayHelper::map($associativeList, $keyField, $valueField);
    }

}
