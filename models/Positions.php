<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "positions".
 *
 * @property int $id 位置ID
 * @property string $serach_address 查找位置姓名
 * @property string $longitude 经度
 * @property string $latitude 纬度
 * @property string $address_name 地址名字
 * @property string $detail_position 详细位置
 * @property string $province 省
 * @property string $city 市
 * @property string $county 县/区
 */
class Positions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'positions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serach_address', 'longitude', 'latitude', 'address_name', 'detail_position', 'province', 'city', 'county'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serach_address' => 'Serach Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'address_name' => 'Address Name',
            'detail_position' => 'Detail Position',
            'province' => 'Province',
            'city' => 'City',
            'county' => 'County',
        ];
    }
}
