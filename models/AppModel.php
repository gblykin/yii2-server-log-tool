<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class AppModel extends \yii\db\ActiveRecord
{
    public static function getList($order = 'title', $key = 'id', $value = 'title'){
        $list = self::find()->select([$key, $value])->asArray(true)->orderBy($order)->all();
        return ArrayHelper::map($list, $key, $value);
    }

    public static function getOrCreate($field, $value){
        $model = static::find()
            ->andWhere([$field => $value])
            ->limit(1)
            ->one();

        if (empty($model)){
            $model = new static([$field => $value]);
            if (!$model->save()){
                throw new Exception(Yii::t('app', 'Model "'. static::class . '" cannot be created'));
            }
        }
        return $model;
    }

    public static function existsValues($field, $list){
        return static::find()
            ->select([$field])
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
                ];
            }

            static::getDb()->createCommand()->batchInsert(
                static::tableName(),
                [$keyField, $valueField],
                $dataList
            )->execute();
        }
    }

    public static function getAssociativeList($keyField, $valueField, $list){
        $associativeList = static::find()
            ->select([$keyField, $valueField])
            ->andWhere(['IN', $keyField, $list])
            ->asArray()
            ->all();

        return ArrayHelper::map($associativeList, $keyField, $valueField);
    }

    public static function getAndGenerateAssociatedList($keyField, $valueField, $list){
        $existsValues = static::existsValues($keyField, $list);
        $notExistsList = array_diff($list, $existsValues);
        if (!empty($notExistsList)){
            static::createList($valueField, $keyField, $notExistsList);
        }

        return static::getAssociativeList($keyField, $valueField, $list);
    }
}