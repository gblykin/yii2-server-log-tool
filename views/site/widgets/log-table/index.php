<?php
use miloschuman\highcharts\Highcharts;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $searchModel app\models\search\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php Pjax::begin(['id' => 'log-table-widget']); ?>
<?php  echo $this->render('_search', ['searchModel' => $searchModel]); ?>
<div class="log-table-widget-content">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'date',
            'requestsCount',
            'urlUrl',
            'browserTitle',
        ]
    ]);
    ?>

</div>
<?php Pjax::end(); ?>

<?php
$this->registerJs(
    '$("document").ready(function(){
            $(document).on("submit", "#log-table-form", function(){
                var data = $(this).serialize();                
                $.pjax.reload({container:"#log-table-widget", data: data});
                return false;
            })
        });'
);
?>
