<?php
namespace app\services\helpers;

use app\helpers\DataHelper;
use app\models\Browser;
use app\models\search\RequestsCountSearch;
use yii\data\BaseDataProvider;

class RequestCountDataHelper
{
    public static function getData(RequestsCountSearch $searchModel, BaseDataProvider $dataProvider){
        $resultData = [];
        $resultData = DataHelper::fillData(
            $searchModel->date_from,
            $searchModel->date_to,
            ['requestsCount' => 0],
        );
        $dataProvider->pagination->setPageSize(0);
        $models = $dataProvider->models;

        foreach ($models as $model){
            $resultData[$model->date]['requestsCount'] = (int)$model->requestsCount;
        }
        return $resultData;
    }
}