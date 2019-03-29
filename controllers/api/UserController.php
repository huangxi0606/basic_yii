<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 10:54
 */
namespace app\controllers\api;

use app\models\LoginForm;

class UserController extends BaseActiveController
{
    public $modelClass = 'app\models\User';

    /**
     * 获取token
     * @return array
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->setAttributes($this->post);
        if ($model->login()) {
            return [
                'code' => 200,
                'message' => '登陆成功',
                'data' => [
                    'access_token' => $model->user->access_token
                ]
            ];
        }
        return [
            'code' => 500,
            'message' => $model->errors
        ];

    }

}