<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "upload".
 *
 * @property int $id ID
 * @property int $uid 用户ID
 * @property string $name 文件名
 * @property string $url 文件地址
 * @property string $size 文件大小
 * @property string $ext 文件后缀名
 * @property int $is_image 是否是图片
 * @property int $created_at 创建时间
 */
class Upload extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'upload';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'is_image', 'created_at'], 'integer'],
            [['name', 'size'], 'string', 'max' => 64],
            [['url'], 'string', 'max' => 255],
            [['ext'], 'string', 'max' => 10],
            [['uid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'name' => 'Name',
            'url' => 'Url',
            'size' => 'Size',
            'ext' => 'Ext',
            'is_image' => 'Is Image',
            'created_at' => 'Created At',
        ];
    }
}
