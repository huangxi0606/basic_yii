<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Articles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="articles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo $form->field($model, 'article_name')->widget(Select2::classname(), [
        'options' => ['placeholder' => '请输入名称 ...'],
        'pluginOptions' => [
            'placeholder' => 'search ...',
            'allowClear' => true,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
            ],
            'ajax' => [
                'url' => \yii\helpers\Url::to(['band']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(res) { return res.text; }'),
            'templateSelection' => new JsExpression('function (res) { return res.text; }'),
        ],
    ]);
    ?>


    <?= $form->field($model, 'cates_id')->dropDownList(\app\models\Articles::forDropDownList())?>

    <?= $form->field($model, 'pic')->widget(\app\widgets\kindeditor\UploadButton::className(),[
        'clientOptions' => [
            'url' => \yii\helpers\Url::to(['upload', 'dir' => 'image']),
        ],
    ]) ?>

    <?= $form->field($model, 'search_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'detail_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'county')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
<?php $this->beginBlock('js') ?>
    $('.quick').hide();
    $("#articles-search_address").keypress(function(){
        if(event.keyCode == 13){
            var name =$('#articles-search_address').val();
            var url = '<?=  \yii\helpers\Url::to(['position']) ?>';
            $.ajax({
                url: url,
                type: 'post',
                data: {name:name},
                success: function (res) {
                    var data = JSON.parse(res);
                    if(data.code =="200"){
                        $('#articles-longitude').val(data.longitude);
                        $('#articles-latitude').val(data.latitude);
                        $('#articles-address_name').val(data.address_name);
                        $('#articles-detail_position').val(data.detail_position);
                        $('#articles-province').val(data.province);
                        $('#articles-city').val(data.city);
                        $('#articles-county').val(data.county);
                    }
                }
            });
        }
        return false;
    });

    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['js'], \yii\web\View::POS_END); ?>

