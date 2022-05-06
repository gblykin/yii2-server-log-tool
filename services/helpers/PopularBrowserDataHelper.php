<?php
namespace app\services\helpers;

use app\helpers\DataHelper;
use app\models\Browser;
use app\models\search\PopularBrowserSearch;
use yii\data\BaseDataProvider;

class PopularBrowserDataHelper
{
    public static function getData(PopularBrowserSearch $searchModel, BaseDataProvider $dataProvider){
        $resultData = [];
        $browsers = Browser::find()
            ->andWhere(['IN', 'id', $searchModel->getMostPopularBrowsersIds()])
            ->all();
        $dataProvider->pagination->setPageSize(0);
        $models = $dataProvider->models;

        foreach ($browsers as $browser){
            $resultData[$browser->id]['title'] = $browser->title;
            $resultData[$browser->id]['data'] = DataHelper::fillData(
                $searchModel->date_from,
                $searchModel->date_to,
                ['usageCount' => 0],
            );
        }

        foreach ($models as $model){
            $resultData[$model->browser_id]['data'][$model->date]['usageCount'] = round($model->usageCount, 2);
        }

        return $resultData;
    }
}