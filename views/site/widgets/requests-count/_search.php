<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $searchModel app\models\search\RequestsCountSearch */
?>
<?php $form = ActiveForm::begin([
        'id' => 'requests-count-form',
        'method' => 'get',
        'action' => '/'
]); ?>

<div class="row align-items-end">
    <div class="col-sm-2">
    <?= $form->field($searchModel, 'date_from')->widget(\yii\jui\DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'clientOptions' => [

        ],
        'options' => ['class' => 'form-control'],
    ]);
    ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($searchModel, 'date_to')->widget(\yii\jui\DatePicker::class, [
            'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [

            ],
            'options' => ['class' => 'form-control'],
        ]);
        ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($searchModel, 'os_id')->dropdownList(\app\models\Os::getList(), ['prompt' => Yii::t('app', 'All')]); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($searchModel, 'architecture_id')->dropdownList(\app\models\Architecture::getList(), ['prompt' => Yii::t('app', 'All')]); ?>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary submit-form-button', 'name' => 'request-count-button']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
