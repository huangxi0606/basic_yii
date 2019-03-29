<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 15:53
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;


class BaseController extends Controller
{
    public function actions() {
        return [
            'upload' => [
                'class' => 'app\widgets\kindeditor\UploadAction',
                'uploadPath' => Yii::$app->params['uploadPath'] . '/',
                'uploadLog' => Yii::$app->params['uploadLog'],
            ],
        ];
    }
}


