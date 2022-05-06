<?php
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\SeriesDataHelper;
use yii\widgets\Pjax;

/* @var $searchModel app\models\search\PopularBrowserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$data = \app\services\helpers\PopularBrowserDataHelper::getData($searchModel, $dataProvider);

$series = [];
foreach ($data as $dataItem){
    $series[] = [
            'name' => $dataItem['title'],
            'data' => array_column($dataItem['data'], 'usageCount')
    ];
}

$categories =[];
if (!empty($data)){
    $categories = array_column(current($data)['data'], 'date');
}
?>

<?php Pjax::begin(['id' => 'popular-browser-widget']); ?>
<?php  echo $this->render('_search', ['searchModel' => $searchModel]); ?>
<div class="popular-browser-widget-content">
    <?php
    echo Highcharts::widget([
        'options' => [
            'title' => ['text' => 'Popular browsers'],
            'xAxis' => [
                'categories' => $categories
            ],
            'yAxis' => [
                'title' => ['text' => Yii::t('app', 'Popular browsers')]
            ],
            'series' => $series,
        ]
    ]);
    ?>
    <?php Pjax::end(); ?>

    <?php
    $this->registerJs(
        '$("document").ready(function(){
            $(document).on("submit", "#popular-browser-form", function(){
                var data = $(this).serialize();                
                $.pjax.reload({container:"#popular-browser-widget", data: data});
                return false;
            })
        });'
    );
    ?>
</div>
