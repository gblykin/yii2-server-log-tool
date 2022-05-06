<?php

/** @var yii\web\View $this */
/* @var $requestsCountSearchModel app\models\search\RequestsCountSearch */
/* @var $requestsCountDataProvider yii\data\ActiveDataProvider */

/* @var $popularBrowserSearchModel app\models\search\PopularBrowserSearch */
/* @var $popularBrowserDataProvider yii\data\ActiveDataProvider */

/* @var $logSearchModel app\models\search\LogSearch */
/* @var $logDataProvider yii\data\ActiveDataProvider */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                <?php
                    echo $this->render('widgets/requests-count/index', [
                        'searchModel' => $requestsCountSearchModel,
                        'dataProvider' => $requestsCountDataProvider,
                    ])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->render('widgets/popular-browser/index', [
                    'searchModel' => $popularBrowserSearchModel,
                    'dataProvider' => $popularBrowserDataProvider,
                ])
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->render('widgets/log-table/index', [
                    'searchModel' => $logSearchModel,
                    'dataProvider' => $logDataProvider,
                ])
                ?>
            </div>
        </div>
    </div>
</div>
