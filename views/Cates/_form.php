<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cates-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parents_id')->dropDownList(\app\models\Cates::forDropDownList())?>

    <?= $form->field($model, 'cate_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([0 => '可用',1 => '禁用']) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
