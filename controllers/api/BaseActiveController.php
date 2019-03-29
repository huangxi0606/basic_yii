<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 10:53
 */
namespace app\controllers\api;

use app\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class BaseActiveController extends ActiveController
{

    public $modelClass = 'app\models\User';

    public $post;
    public $get;
    public $_user;
    public $_userId;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->_user = User::findIdentityByAccessToken(\Yii::$app->request->headers->get('Authorization'));
    }

    /**
     * @return array
     */

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'optional' => ['login']
        ];

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];

        return $behaviors;
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        parent::beforeAction($action);
        $this->post = \Yii::$app->request->post();
        $this->get = \Yii::$app->request->get();
        $this->_user = \Yii::$app->user->identity;
        $this->_userId = \Yii::$app->user->id;
        return $action;
    }

}