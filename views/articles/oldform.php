<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Articles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="articles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'article_name')->textInput(['maxlength' => true]) ?>

    <div class="quick">
    </div>

    <?= $form->field($model, 'cates_id')->dropDownList(\app\models\Articles::forDropDownList())?>

    <?= $form->field($model, 'pic')->widget(\app\widgets\kindeditor\UploadButton::className(),[
        'clientOptions' => [
            'url' => \yii\helpers\Url::to(['upload', 'dir' => 'image']),
        ],
    ])
    ?>

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
    // search address
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
    // association search
    $("#articles-article_name").keydown(function(){
        var keyword =$.trim($("#articles-article_name").val());
        serachband()
        return false;
    });
    function serachband() {
        var keyword =$.trim($("#articles-article_name").val());
        if(keyword){
            var url = '<?=  \yii\helpers\Url::to(['band']) ?>';
            $.ajax({
                url: url,
                type: 'post',
                data: {keyword:keyword},
                success: function (res) {
                    if(res){
                        console.log(res)
                        var data = JSON.parse(res);
                        var html ='';
                        $.each(data, function(i, item){
                            $.each(item, function(c,v){
                                console.log(v);
                                $('.quick').show();
                                html += '<option>'+ v +'</option>'
                                $('.quick').append(html);
                            });
                        });

                    }
                }
            });
        }

    }


    <?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['js'], \yii\web\View::POS_END); ?>

