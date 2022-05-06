<?php
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\SeriesDataHelper;
use yii\widgets\Pjax;

/* @var $searchModel app\models\search\RequestsCountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$data = \app\services\helpers\RequestCountDataHelper::getData($searchModel, $dataProvider);

if (!empty($data)){
    $categories =  array_column($data, 'date');
    $series =  array_column($data, 'requestsCount');
}
?>

<?php Pjax::begin(['id' => 'requests-count-widget']); ?>
<?php  echo $this->render('_search', ['searchModel' => $searchModel]); ?>
<div>
    <?php
    echo Highcharts::widget([
        'options' => [
            'title' => ['text' => Yii::t('app', 'Requests count')],
            'xAxis' => [
                'categories' => $categories
            ],
            'yAxis' => [
                'title' => ['text' => Yii::t('app', 'Requests count')]
            ],
            'series' => [
                ['name' => Yii::t('app', 'Requests count'), 'data' => $series],
            ]
        ]
    ]);

    ?>
    <?php Pjax::end(); ?>

    <?php
    $this->registerJs(
        '$("document").ready(function(){
            $(document).on("submit", "#requests-count-form", function(){
                var data = $(this).serialize();                
                $.pjax.reload({container:"#requests-count-widget", data: data});
                return false;
            })
        });'
    );
    ?>
</div>
