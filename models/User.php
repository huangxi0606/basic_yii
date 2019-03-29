<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }


    public function rules()
    {
        return [
            ['username', 'required', 'message' => '用户名不能为空'],
            ['api_token', 'required', 'message' => 'api_token不能为空']
        ];
    }

//    /**
//     * @inheritdoc
//     */
//    public function attributeLabels()
//    {
//        ...
//    }

    /**
     * 根据用户名查找用户
     * Finds an identity by username
     * @param null $username
     * @return null|static
     */
    public static function findByUsername($username = null)
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    /**
     * 生成随机的token并加上时间戳
     * Generated random accessToken with timestamp
     * @throws \yii\base\Exception
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString() . '-' . time();
    }

    /**
     * 验证token是否过期
     * Validates if accessToken expired
     * @param null $token
     * @return bool
     */
    public static function validateAccessToken($token = null)
    {
        if ($token === null) {
            return false;
        } else {
            $timestamp = (int)substr($token, strrpos($token, '-') + 1);
            $expire = Yii::$app->params['accessTokenExpire'];
            return $timestamp + $expire >= time();
        }
    }

}